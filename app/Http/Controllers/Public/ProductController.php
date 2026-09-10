<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::query()->ofType('product')->active()->ordered()->withCount(['products' => fn ($q) => $q->active()])->get();
        $current = null;

        $query = Product::query()->with('category')->active()->ordered();

        if ($request->filled('kategori')) {
            $current = $categories->firstWhere('slug', $request->string('kategori')->toString());
            abort_unless($current, 404);
            $query->where('category_id', $current->id);
        }

        if ($request->filled('q')) {
            $term = $request->string('q')->limit(100)->toString();
            $query->where(fn ($q) => $q->where('title', 'like', "%{$term}%")->orWhere('sku', 'like', "%{$term}%"));
        }

        return view('template::products.index', [
            'products' => $query->paginate(config('cms.pagination.public'))->withQueryString(),
            'categories' => $categories,
            'currentCategory' => $current,
        ]);
    }

    public function show(string $slug): View
    {
        $product = Product::query()->with('category')->active()->where('slug', $slug)->firstOrFail();

        return view('template::products.show', [
            'product' => $product,
            'related' => Product::query()->with('category')->active()->ordered()
                ->when($product->category_id, fn ($q) => $q->where('category_id', $product->category_id))
                ->whereKeyNot($product->id)->limit(4)->get(),
        ]);
    }
}
