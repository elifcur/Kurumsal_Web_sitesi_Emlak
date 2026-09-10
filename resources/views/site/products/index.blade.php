@extends('template::layouts.app')
@section('content')
    @php($heading = $currentCategory?->name ?? setting('products_label', 'Ürünler'))
    @section('seo')<x-seo :title="$heading" :description="$currentCategory?->description ?: setting('products_intro')" :robots="request()->hasAny(['q', 'page']) ? 'noindex, follow' : 'index, follow'" />@endsection
    <x-page-header :title="$heading" :subtitle="$currentCategory?->description ?: setting('products_intro')" :breadcrumbs="array_values(array_filter([['label' => setting('products_label', 'Ürünler'), 'url' => route('products.index')], $currentCategory ? ['label' => $currentCategory->name] : null]))" />
    <section class="section">
        <div class="container-x grid gap-10 lg:grid-cols-4">
            <aside class="space-y-6">
                <form method="get" action="{{ route('products.index') }}" class="relative">
                    @if($currentCategory)<input type="hidden" name="kategori" value="{{ $currentCategory->slug }}">@endif
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Ürünlerde ara..." class="form-input !pr-10">
                    <button type="submit" class="absolute top-1/2 right-3 -translate-y-1/2 text-slate-400 hover:text-primary" aria-label="Ara"><x-icon name="search" /></button>
                </form>
                @if($categories->isNotEmpty())
                    <div class="rounded-lg border border-slate-200 p-5">
                        <h3 class="font-display font-semibold text-ink">Kategoriler</h3>
                        <ul class="mt-3 space-y-1 text-sm">
                            <li><a href="{{ route('products.index') }}" @class(['flex justify-between rounded-lg px-3 py-2', 'bg-primary-light text-primary font-medium' => ! $currentCategory, 'hover:bg-slate-50' => $currentCategory])>Tümü</a></li>
                            @foreach($categories as $category)
                                <li><a href="{{ route('products.index', ['kategori' => $category->slug]) }}" @class(['flex justify-between rounded-lg px-3 py-2', 'bg-primary-light text-primary font-medium' => $currentCategory?->id === $category->id, 'hover:bg-slate-50' => $currentCategory?->id !== $category->id])><span>{{ $category->name }}</span><span class="text-xs text-slate-400">{{ $category->products_count }}</span></a></li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </aside>
            <div class="lg:col-span-3">
                @if($products->isEmpty())
                    <x-empty-state icon="box" title="Ürün bulunamadı" text="Arama veya kategori seçimini değiştirerek tekrar deneyin." />
                @else
                    <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">@foreach($products as $product)<x-product-card :product="$product" />@endforeach</div>
                    <div class="mt-10">{{ $products->links() }}</div>
                @endif
            </div>
        </div>
    </section>
@endsection
