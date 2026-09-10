<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function show(): View
    {
        return view('template::contact');
    }

    public function store(ContactRequest $request): RedirectResponse
    {
        $message = ContactMessage::query()->create([
            'type' => 'contact',
            ...$request->safe()->only(['name', 'email', 'phone', 'subject', 'message']),
            'ip' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 500),
        ]);

        self::notify($message);

        return redirect()->route('contact.show')->with('success', 'Mesajınız başarıyla iletildi. En kısa sürede size dönüş yapacağız.');
    }

    /**
     * Sends the notification e-mail to the configured recipient without breaking the request on mail failure.
     */
    public static function notify(ContactMessage $message): void
    {
        $recipient = setting('contact_recipient') ?: setting('email');

        if (! $recipient) {
            return;
        }

        try {
            Mail::to($recipient)->send(new ContactMessageReceived($message));
        } catch (\Throwable $e) {
            Log::warning('Contact notification mail failed: '.$e->getMessage());
        }
    }
}
