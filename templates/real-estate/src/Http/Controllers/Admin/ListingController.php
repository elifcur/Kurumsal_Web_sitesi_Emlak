<?php

namespace Templates\RealEstate\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Templates\RealEstate\Http\Requests\ListingRequest;
use Templates\RealEstate\Models\Listing;

class ListingController extends Controller
{
    public function index(Request $request): View
    {
        $listings = Listing::query()
            ->with('agent')
            ->when($request->filled('q'), fn ($q) => $q->where('title', 'like', '%'.$request->string('q')->limit(100).'%'))
            ->when($request->filled('type'), fn ($q) => $q->where('listing_type', $request->input('type')))
            ->when($request->input('status') === 'active', fn ($q) => $q->where('is_active', true))
            ->when($request->input('status') === 'inactive', fn ($q) => $q->where('is_active', false))
            ->ordered()
            ->paginate(config('cms.pagination.admin'))
            ->withQueryString();

        return view('template::admin.listings.index', ['listings' => $listings]);
    }

    public function create(): View
    {
        return view('template::admin.listings.form', [
            'listing' => new Listing(['is_active' => true, 'listing_type' => 'sale', 'property_type' => 'apartment', 'currency' => 'TRY']),
            'agents' => $this->agentOptions(),
        ]);
    }

    public function store(ListingRequest $request): RedirectResponse
    {
        $listing = Listing::query()->create($request->payload());

        return redirect()->route('admin.listings.edit', $listing)->with('success', 'İlan oluşturuldu.');
    }

    public function edit(Listing $listing): View
    {
        return view('template::admin.listings.form', ['listing' => $listing, 'agents' => $this->agentOptions()]);
    }

    public function update(ListingRequest $request, Listing $listing): RedirectResponse
    {
        $listing->update($request->payload());

        return redirect()->route('admin.listings.edit', $listing)->with('success', 'İlan güncellendi.');
    }

    public function destroy(Listing $listing): RedirectResponse
    {
        $listing->delete();

        return redirect()->route('admin.listings.index')->with('success', 'İlan silindi.');
    }

    /**
     * @return array<int, string>
     */
    private function agentOptions(): array
    {
        return TeamMember::query()->active()->ordered()->pluck('name', 'id')->all();
    }
}
