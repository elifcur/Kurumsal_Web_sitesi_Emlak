<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PartnerRequest;
use App\Models\Partner;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function index(Request $request): View
    {
        $partners = Partner::query()
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%'.$request->string('q')->limit(100).'%'))
            ->ordered()
            ->paginate(config('cms.pagination.admin'))
            ->withQueryString();

        return view('admin.partners.index', ['partners' => $partners]);
    }

    public function create(): View
    {
        return view('admin.partners.form', ['partner' => new Partner(['is_active' => true, 'sort_order' => 0])]);
    }

    public function store(PartnerRequest $request): RedirectResponse
    {
        Partner::query()->create($request->payload());

        return redirect()->route('admin.partners.index')->with('success', 'Partner oluşturuldu.');
    }

    public function edit(Partner $partner): View
    {
        return view('admin.partners.form', ['partner' => $partner]);
    }

    public function update(PartnerRequest $request, Partner $partner): RedirectResponse
    {
        $partner->update($request->payload());

        return redirect()->route('admin.partners.index')->with('success', 'Partner güncellendi.');
    }

    public function destroy(Partner $partner): RedirectResponse
    {
        $partner->delete();

        return redirect()->route('admin.partners.index')->with('success', 'Partner silindi.');
    }
}
