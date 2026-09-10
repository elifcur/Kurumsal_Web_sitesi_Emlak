<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PageRequest;
use App\Models\Page;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PageController extends Controller
{
    public function index(Request $request): View
    {
        $pages = Page::query()
            ->when($request->filled('q'), fn ($q) => $q->where('title', 'like', '%'.$request->string('q')->limit(100).'%'))
            ->when($request->input('status') === 'published', fn ($q) => $q->where('is_published', true))
            ->when($request->input('status') === 'draft', fn ($q) => $q->where('is_published', false))
            ->ordered()
            ->paginate(config('cms.pagination.admin'))
            ->withQueryString();

        return view('admin.pages.index', ['pages' => $pages]);
    }

    public function create(): View
    {
        return view('admin.pages.form', ['page' => new Page(['template' => 'default', 'is_published' => true])]);
    }

    public function store(PageRequest $request): RedirectResponse
    {
        $page = Page::query()->create($request->payload());
        $this->flushNavigation();

        return redirect()->route('admin.pages.edit', $page)->with('success', 'Sayfa oluşturuldu.');
    }

    public function edit(Page $page): View
    {
        return view('admin.pages.form', ['page' => $page]);
    }

    public function update(PageRequest $request, Page $page): RedirectResponse
    {
        $page->update($request->payload());
        $this->flushNavigation();

        return redirect()->route('admin.pages.edit', $page)->with('success', 'Sayfa güncellendi.');
    }

    public function destroy(Page $page): RedirectResponse
    {
        if ($page->is_system) {
            return back()->with('error', 'Sistem sayfaları silinemez. Yayından kaldırabilirsiniz.');
        }

        $page->delete();
        $this->flushNavigation();

        return redirect()->route('admin.pages.index')->with('success', 'Sayfa silindi.');
    }

    private function flushNavigation(): void
    {
        Cache::forget('nav.menu_pages');
        Cache::forget('nav.footer_pages');
    }
}
