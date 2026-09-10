<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::query()
            ->with('category')
            ->when($request->filled('q'), function ($q) use ($request): void {
                $term = $request->string('q')->limit(100);
                $q->where(fn ($w) => $w->where('title', 'like', "%{$term}%")->orWhere('sku', 'like', "%{$term}%"));
            })
            ->when($request->filled('category'), fn ($q) => $q->where('category_id', $request->integer('category')))
            ->when($request->input('status') === 'active', fn ($q) => $q->where('is_active', true))
            ->when($request->input('status') === 'inactive', fn ($q) => $q->where('is_active', false))
            ->ordered()
            ->paginate(config('cms.pagination.admin'))
            ->withQueryString();

        return view('admin.products.index', ['products' => $products, 'categories' => $this->categoryOptions()]);
    }

    public function create(): View
    {
        return view('admin.products.form', [
            'product' => new Product(['is_active' => true]),
            'categories' => $this->categoryOptions(),
        ]);
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        $product = Product::query()->create($request->payload());
        Cache::forget('nav.has_products');

        return redirect()->route('admin.products.edit', $product)->with('success', 'Ürün oluşturuldu.');
    }

    public function edit(Product $product): View
    {
        return view('admin.products.form', ['product' => $product, 'categories' => $this->categoryOptions()]);
    }

    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $product->update($request->payload());
        Cache::forget('nav.has_products');

        return redirect()->route('admin.products.edit', $product)->with('success', 'Ürün güncellendi.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();
        Cache::forget('nav.has_products');

        return redirect()->route('admin.products.index')->with('success', 'Ürün silindi.');
    }

    /**
     * @return array<int, string>
     */
    private function categoryOptions(): array
    {
        return Category::query()->ofType('product')->ordered()->pluck('name', 'id')->all();
    }
}
