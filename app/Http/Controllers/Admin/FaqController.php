<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FaqRequest;
use App\Models\Faq;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index(Request $request): View
    {
        $faqs = Faq::query()
            ->when($request->filled('q'), fn ($q) => $q->where('question', 'like', '%'.$request->string('q')->limit(100).'%'))
            ->ordered()
            ->paginate(config('cms.pagination.admin'))
            ->withQueryString();

        return view('admin.faqs.index', ['faqs' => $faqs]);
    }

    public function create(): View
    {
        return view('admin.faqs.form', ['faq' => new Faq(['is_active' => true, 'sort_order' => (int) Faq::query()->max('sort_order') + 1])]);
    }

    public function store(FaqRequest $request): RedirectResponse
    {
        Faq::query()->create($request->payload());

        return redirect()->route('admin.faqs.index')->with('success', 'Soru oluşturuldu.');
    }

    public function edit(Faq $faq): View
    {
        return view('admin.faqs.form', ['faq' => $faq]);
    }

    public function update(FaqRequest $request, Faq $faq): RedirectResponse
    {
        $faq->update($request->payload());

        return redirect()->route('admin.faqs.index')->with('success', 'Soru güncellendi.');
    }

    public function destroy(Faq $faq): RedirectResponse
    {
        $faq->delete();

        return redirect()->route('admin.faqs.index')->with('success', 'Soru silindi.');
    }

    public function reorder(Request $request): JsonResponse
    {
        $data = $request->validate(['order' => ['required', 'array', 'max:500'], 'order.*' => ['integer']]);

        foreach (array_values($data['order']) as $position => $id) {
            Faq::query()->whereKey($id)->update(['sort_order' => $position]);
        }

        return response()->json(['ok' => true]);
    }
}
