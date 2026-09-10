<?php

namespace Templates\RealEstate\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Public\ContactController;
use App\Models\ContactMessage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Templates\RealEstate\Models\Listing;

class ListingController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'tur' => ['nullable', Rule::in(array_keys(Listing::TYPES))],
            'tip' => ['nullable', Rule::in(array_keys(Listing::PROPERTY_TYPES))],
            'sehir' => ['nullable', 'string', 'max:80'],
            'oda' => ['nullable', 'string', 'max:20'],
            'min_fiyat' => ['nullable', 'numeric', 'min:0'],
            'max_fiyat' => ['nullable', 'numeric', 'min:0'],
            'q' => ['nullable', 'string', 'max:100'],
            'sirala' => ['nullable', Rule::in(['yeni', 'fiyat-artan', 'fiyat-azalan'])],
        ]);

        $listings = Listing::query()->active()
            ->when($filters['tur'] ?? null, fn ($q, $v) => $q->where('listing_type', $v))
            ->when($filters['tip'] ?? null, fn ($q, $v) => $q->where('property_type', $v))
            ->when($filters['sehir'] ?? null, fn ($q, $v) => $q->where('city', $v))
            ->when($filters['oda'] ?? null, fn ($q, $v) => $q->where('rooms', $v))
            ->when($filters['min_fiyat'] ?? null, fn ($q, $v) => $q->where('price', '>=', $v))
            ->when($filters['max_fiyat'] ?? null, fn ($q, $v) => $q->where('price', '<=', $v))
            ->when($filters['q'] ?? null, fn ($q, $v) => $q->where(fn ($w) => $w->where('title', 'like', "%{$v}%")->orWhere('district', 'like', "%{$v}%")->orWhere('city', 'like', "%{$v}%")))
            ->when(($filters['sirala'] ?? 'yeni') === 'fiyat-artan', fn ($q) => $q->orderBy('price'))
            ->when(($filters['sirala'] ?? 'yeni') === 'fiyat-azalan', fn ($q) => $q->orderByDesc('price'))
            ->ordered()
            ->paginate(config('cms.pagination.public'))
            ->withQueryString();

        return view('template::listings.index', [
            'listings' => $listings,
            'filters' => $filters,
            'cities' => Listing::query()->active()->whereNotNull('city')->distinct()->orderBy('city')->pluck('city'),
            'roomOptions' => Listing::query()->active()->whereNotNull('rooms')->distinct()->orderBy('rooms')->pluck('rooms'),
        ]);
    }

    public function show(string $slug): View
    {
        $listing = Listing::query()->with('agent')->active()->where('slug', $slug)->firstOrFail();

        return view('template::listings.show', [
            'listing' => $listing,
            'similar' => Listing::query()->active()->where('listing_type', $listing->listing_type)->whereKeyNot($listing->id)->ordered()->limit(3)->get(),
        ]);
    }

    public function inquiry(Request $request, string $slug): RedirectResponse
    {
        $listing = Listing::query()->active()->where('slug', $slug)->firstOrFail();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190'],
            'phone' => ['required', 'string', 'max:40'],
            'message' => ['nullable', 'string', 'max:2000'],
            'kvkk' => ['accepted'],
            'website' => ['prohibited'],
        ], ['kvkk.accepted' => 'KVKK metnini onaylamalısınız.']);

        $message = ContactMessage::query()->create([
            'type' => 'listing',
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'subject' => 'İlan Talebi: '.$listing->title,
            'message' => ($data['message'] ?? null) ?: 'İlan hakkında bilgi almak istiyorum.',
            'meta' => ['İlan' => $listing->title, 'İlan URL' => $listing->url(), 'Tür' => $listing->typeLabel(), 'Fiyat' => $listing->formattedPrice()],
            'ip' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 500),
        ]);

        ContactController::notify($message);

        return back()->with('success', setting('inquiry_success', 'Talebiniz alındı. Danışmanımız en kısa sürede sizinle iletişime geçecek.'));
    }
}
