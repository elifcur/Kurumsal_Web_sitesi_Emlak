<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ContactMessageController extends Controller
{
    public function index(Request $request): View
    {
        $messages = ContactMessage::query()
            ->when($request->filled('q'), function ($q) use ($request): void {
                $term = $request->string('q')->limit(100);
                $q->where(fn ($w) => $w->where('name', 'like', "%{$term}%")->orWhere('email', 'like', "%{$term}%")->orWhere('subject', 'like', "%{$term}%"));
            })
            ->when($request->input('status') === 'unread', fn ($q) => $q->where('is_read', false))
            ->when($request->input('status') === 'read', fn ($q) => $q->where('is_read', true))
            ->when($request->filled('type'), fn ($q) => $q->where('type', $request->input('type')))
            ->latest()
            ->paginate(config('cms.pagination.admin'))
            ->withQueryString();

        return view('admin.messages.index', ['messages' => $messages, 'types' => config('cms.message_types')]);
    }

    public function show(ContactMessage $message): View
    {
        $message->markAsRead();

        return view('admin.messages.show', ['message' => $message]);
    }

    public function toggleRead(ContactMessage $message): RedirectResponse
    {
        $message->is_read ? $message->markAsUnread() : $message->markAsRead();

        return back()->with('success', $message->is_read ? 'Okundu olarak işaretlendi.' : 'Okunmadı olarak işaretlendi.');
    }

    public function destroy(ContactMessage $message): RedirectResponse
    {
        $message->delete();

        return redirect()->route('admin.messages.index')->with('success', 'Mesaj silindi.');
    }

    public function bulk(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'action' => ['required', Rule::in(['read', 'unread', 'delete'])],
            'ids' => ['required', 'array', 'max:200'],
            'ids.*' => ['integer', 'exists:contact_messages,id'],
        ]);

        $query = ContactMessage::query()->whereKey($data['ids']);

        match ($data['action']) {
            'read' => $query->update(['is_read' => true, 'read_at' => now()]),
            'unread' => $query->update(['is_read' => false, 'read_at' => null]),
            'delete' => $query->delete(),
        };

        return back()->with('success', count($data['ids']).' mesaj işlendi.');
    }
}
