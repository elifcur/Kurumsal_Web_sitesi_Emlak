<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    private const TYPES = ['product' => 'Ürün Kategorisi', 'post' => 'Blog Kategorisi'];

    public function index(Request $request): View
    {
        $type = in_array($request->input('type'), array_keys(self::TYPES), true) ? $request->input('type') : 'product';

        $categories = Category::query()
            ->with('parent')
            ->withCount(['products', 'posts'])
            ->ofType($type)
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%'.$request->string('q')->limit(100).'%'))
            ->ordered()
            ->paginate(config('cms.pagination.admin'))
            ->withQueryString();

        return view('admin.categories.index', ['categories' => $categories, 'type' => $type, 'types' => self::TYPES]);
    }

    public function create(Request $request): View
    {
        $type = in_array($request->input('type'), array_keys(self::TYPES), true) ? $request->input('type') : 'product';

        return view('admin.categories.form', [
            'category' => new Category(['type' => $type, 'is_active' => true]),
            'parents' => $this->parentOptions($type),
            'types' => self::TYPES,
        ]);
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        $category = Category::query()->create($request->payload());

        return redirect()->route('admin.categories.index', ['type' => $category->type])->with('success', 'Kategori oluşturuldu.');
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.form', [
            'category' => $category,
            'parents' => $this->parentOptions($category->type, $category->id),
            'types' => self::TYPES,
        ]);
    }

    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        $category->update($request->payload());

        return redirect()->route('admin.categories.index', ['type' => $category->type])->with('success', 'Kategori güncellendi.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        return redirect()->route('admin.categories.index', ['type' => $category->type])->with('success', 'Kategori silindi. Bağlı içerikler kategorisiz kaldı.');
    }

    /**
     * @return array<int, string>
     */
    private function parentOptions(string $type, ?int $except = null): array
    {
        return Category::query()->ofType($type)->whereNull('parent_id')
            ->when($except, fn ($q) => $q->whereKeyNot($except))
            ->ordered()->pluck('name', 'id')->all();
    }
}
