@extends('template::layouts.app')
@section('content')
    @php($heading = $currentCategory?->name ?? ($currentTag ? '#'.$currentTag->name : $title))
    @section('seo')<x-seo :title="$heading" :description="$currentCategory?->description ?: setting('blog_intro')" :robots="request()->hasAny(['q', 'page', 'etiket']) ? 'noindex, follow' : 'index, follow'" />@endsection
    <x-page-header :title="$heading" :subtitle="setting('blog_intro', 'Emlak piyasası, yatırım ve kiralama hakkında faydalı içerikler.')" :breadcrumbs="array_values(array_filter([['label' => $title, 'url' => route('blog.index')], $currentCategory ? ['label' => $currentCategory->name] : null, $currentTag ? ['label' => $currentTag->name] : null]))" />
    <section class="section">
        <div class="container-x grid gap-10 lg:grid-cols-4">
            <div class="lg:col-span-3">
                @if($posts->isEmpty())
                    <x-empty-state icon="newspaper" title="Yazı bulunamadı" />
                @else
                    <div class="grid gap-6 md:grid-cols-2">@foreach($posts as $post)<x-post-card :post="$post" />@endforeach</div>
                    <div class="mt-10">{{ $posts->links() }}</div>
                @endif
            </div>
            <aside class="space-y-6">
                <form method="get" action="{{ route('blog.index') }}" class="relative">
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Yazılarda ara..." class="form-input !pr-10">
                    <button type="submit" class="absolute top-1/2 right-3 -translate-y-1/2 text-slate-400 hover:text-primary" aria-label="Ara"><x-icon name="search" /></button>
                </form>
                @if($categories->isNotEmpty())
                    <div class="rounded-lg border border-slate-200 p-5">
                        <h3 class="font-display font-semibold text-ink">Kategoriler</h3>
                        <ul class="mt-3 space-y-1 text-sm">
                            @foreach($categories as $category)
                                <li><a href="{{ route('blog.index', ['kategori' => $category->slug]) }}" @class(['flex justify-between rounded-lg px-3 py-2', 'bg-primary-light text-primary font-medium' => $currentCategory?->id === $category->id, 'hover:bg-slate-50' => $currentCategory?->id !== $category->id])><span>{{ $category->name }}</span><span class="text-xs text-slate-400">{{ $category->posts_count }}</span></a></li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </aside>
        </div>
    </section>
@endsection
