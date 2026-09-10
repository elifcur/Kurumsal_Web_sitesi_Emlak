<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request): View
    {
        $query = Post::query()->with(['category', 'author'])->published()->latestPublished();
        $title = setting('blog_label', 'Blog');
        $currentCategory = null;
        $currentTag = null;

        if ($request->filled('kategori')) {
            $currentCategory = Category::query()->ofType('post')->active()->where('slug', $request->string('kategori')->toString())->firstOrFail();
            $query->where('category_id', $currentCategory->id);
        }

        if ($request->filled('etiket')) {
            $currentTag = Tag::query()->where('slug', $request->string('etiket')->toString())->firstOrFail();
            $query->whereHas('tags', fn ($q) => $q->whereKey($currentTag->id));
        }

        if ($request->filled('q')) {
            $term = $request->string('q')->limit(100)->toString();
            $query->where(fn ($q) => $q->where('title', 'like', "%{$term}%")->orWhere('excerpt', 'like', "%{$term}%"));
        }

        return view('template::blog.index', [
            'posts' => $query->paginate(config('cms.pagination.public'))->withQueryString(),
            'categories' => Category::query()->ofType('post')->active()->ordered()->withCount(['posts' => fn ($q) => $q->published()])->get(),
            'currentCategory' => $currentCategory,
            'currentTag' => $currentTag,
            'title' => $title,
        ]);
    }

    public function show(string $slug): View
    {
        $post = Post::query()->with(['category', 'author', 'tags'])->published()->where('slug', $slug)->firstOrFail();

        Post::query()->whereKey($post->id)->increment('views');

        return view('template::blog.show', [
            'post' => $post,
            'related' => Post::query()->with('category')->published()->latestPublished()
                ->when($post->category_id, fn ($q) => $q->where('category_id', $post->category_id))
                ->whereKeyNot($post->id)->limit(3)->get(),
        ]);
    }
}
