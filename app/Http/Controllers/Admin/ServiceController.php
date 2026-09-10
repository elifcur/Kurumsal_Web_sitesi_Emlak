<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ServiceRequest;
use App\Models\Service;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ServiceController extends Controller
{
    public function index(Request $request): View
    {
        $services = Service::query()
            ->when($request->filled('q'), fn ($q) => $q->where('title', 'like', '%'.$request->string('q')->limit(100).'%'))
            ->when($request->input('status') === 'active', fn ($q) => $q->where('is_active', true))
            ->when($request->input('status') === 'inactive', fn ($q) => $q->where('is_active', false))
            ->ordered()
            ->paginate(config('cms.pagination.admin'))
            ->withQueryString();

        return view('admin.services.index', ['services' => $services]);
    }

    public function create(): View
    {
        return view('admin.services.form', ['service' => new Service(['is_active' => true, 'sort_order' => Service::query()->max('sort_order') + 1])]);
    }

    public function store(ServiceRequest $request): RedirectResponse
    {
        $service = Service::query()->create($request->payload());
        $this->flushCache();

        return redirect()->route('admin.services.edit', $service)->with('success', 'Hizmet oluşturuldu.');
    }

    public function edit(Service $service): View
    {
        return view('admin.services.form', ['service' => $service]);
    }

    public function update(ServiceRequest $request, Service $service): RedirectResponse
    {
        $service->update($request->payload());
        $this->flushCache();

        return redirect()->route('admin.services.edit', $service)->with('success', 'Hizmet güncellendi.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();
        $this->flushCache();

        return redirect()->route('admin.services.index')->with('success', 'Hizmet silindi.');
    }

    /**
     * Persist a new ordering: expects {"order": [id, id, ...]}.
     */
    public function reorder(Request $request): JsonResponse
    {
        $data = $request->validate(['order' => ['required', 'array', 'max:500'], 'order.*' => ['integer']]);

        foreach (array_values($data['order']) as $position => $id) {
            Service::query()->whereKey($id)->update(['sort_order' => $position]);
        }

        $this->flushCache();

        return response()->json(['ok' => true]);
    }

    private function flushCache(): void
    {
        Cache::forget('nav.footer_services');
        Cache::forget('nav.has_services');
    }
}
