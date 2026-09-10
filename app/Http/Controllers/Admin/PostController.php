<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PostStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index(Request $request): View
    {
        $posts = Post::query()
            ->with(['category', 'author'])
            ->when($request->filled('q'), fn ($q) => $q->where('title', 'like', '%'.$request->string('q')->limit(100).'%'))
            ->when($request->filled('category'), fn ($q) => $q->where('category_id', $request->integer('category')))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(config('cms.pagination.admin'))
            ->withQueryString();

        return view('admin.posts.index', ['posts' => $posts, 'categories' => $this->categoryOptions()]);
    }

    public function create(): View
    {
        return view('admin.posts.form', [
            'post' => new Post(['status' => PostStatus::Draft, 'published_at' => now()]),
            'categories' => $this->categoryOptions(),
            'tags' => '',
        ]);
    }

    public function store(PostRequest $request): RedirectResponse
    {
        $post = Post::query()->create($request->payload() + ['user_id' => $request->user()->id]);
        $post->tags()->sync($this->tagIds($request->tagNames()));

        return redirect()->route('admin.posts.edit', $post)->with('success', 'Yazı oluşturuldu.');
    }

    public function edit(Post $post): View
    {
        $post->load('tags');

        return view('admin.posts.form', [
            'post' => $post,
            'categories' => $this->categoryOptions(),
            'tags' => $post->tags->pluck('name')->implode(', '),
        ]);
    }

    public function update(PostRequest $request, Post $post): RedirectResponse
    {
        $post->update($request->payload());
        $post->tags()->sync($this->tagIds($request->tagNames()));

        return redirect()->route('admin.posts.edit', $post)->with('success', 'Yazı güncellendi.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        $post->delete();

        return redirect()->route('admin.posts.index')->with('success', 'Yazı silindi.');
    }

    /**
     * @param  array<int, string>  $names
     * @return array<int, int>
     */
    private function tagIds(array $names): array
    {
        return collect($names)
            ->map(fn (string $name) => Tag::query()->firstOrCreate(['slug' => Str::slug($name, '-', 'tr')], ['name' => $name])->id)
            ->all();
    }

    /**
     * @return array<int, string>
     */
    private function categoryOptions(): array
    {
        return Category::query()->ofType('post')->ordered()->pluck('name', 'id')->all();
    }
}
