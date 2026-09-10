@extends('template::layouts.app')
@section('seo')<x-seo :title="$product->meta_title ?: $product->title" :description="$product->meta_description ?: $product->excerpt" :image="$product->image" type="product" />@endsection
@section('content')
    <x-page-header :title="$product->title" :subtitle="$product->excerpt" :breadcrumbs="array_values(array_filter([['label' => setting('products_label', 'Ürünler'), 'url' => route('products.index')], $product->category ? ['label' => $product->category->name, 'url' => route('products.index', ['kategori' => $product->category->slug])] : null, ['label' => $product->title]]))" />
    <section class="section">
        <div class="container-x grid gap-12 lg:grid-cols-5">
            <div class="lg:col-span-3" x-data="gallery(@js(collect([$product->image, ...($product->gallery ?? [])])->filter()->map(fn (string $path) => upload_url($path))->values()->all()))">
                <div class="aspect-[4/3] overflow-hidden rounded-lg border border-slate-200 bg-sand">
                    <template x-if="current"><img :src="current" alt="{{ $product->title }}" class="h-full w-full object-cover"></template>
                    <div x-show="!current" class="flex h-full items-center justify-center text-primary/30"><x-icon name="box" class="h-24 w-24" /></div>
                </div>
                <div x-show="images.length > 1" x-cloak class="mt-3 flex gap-2 overflow-x-auto">
                    <template x-for="(img, i) in images" :key="i">
                        <button type="button" @click="index = i" class="h-20 w-24 shrink-0 overflow-hidden rounded-md border-2" :class="i === index ? 'border-primary' : 'border-transparent'"><img :src="img" alt="" class="h-full w-full object-cover"></button>
                    </template>
                </div>
            </div>
            <div class="lg:col-span-2">
                @if($product->category)<a href="{{ route('products.index', ['kategori' => $product->category->slug]) }}" class="chip hover:bg-primary-light hover:text-primary">{{ $product->category->name }}</a>@endif
                @if($product->sku)<p class="mt-3 text-xs tracking-wide text-slate-500 uppercase">Ürün Kodu: {{ $product->sku }}</p>@endif
                @if($product->formattedPrice())
                    <p class="mt-4 font-display text-4xl text-ink">{{ $product->formattedPrice() }}</p>
                    @if($product->price_note)<p class="mt-1 text-sm text-slate-500">{{ $product->price_note }}</p>@endif
                @endif
                @if(! empty($product->specs))
                    <dl class="mt-8 divide-y divide-slate-100 rounded-lg border border-slate-200 text-sm">
                        @foreach($product->specs as $spec)<div class="flex justify-between gap-4 px-5 py-3"><dt class="text-slate-500">{{ $spec['label'] }}</dt><dd class="text-right font-medium text-ink">{{ $spec['value'] }}</dd></div>@endforeach
                    </dl>
                @endif
                <div class="mt-8 rounded-lg bg-ink p-6 text-white">
                    <h3 class="font-display text-lg font-bold">Bu ürün hakkında bilgi alın</h3>
                    <p class="mt-2 text-sm text-slate-300">Fiyat, stok ve teslimat için bize ulaşın.</p>
                    <a href="{{ route('contact.show', ['konu' => 'Teklif almak istiyorum']) }}" class="btn-primary mt-5 w-full">Teklif İste</a>
                    @if(setting('phone'))<a href="{{ phone_href(setting('phone')) }}" class="mt-3 flex items-center justify-center gap-2 text-sm font-semibold"><x-icon name="phone" class="h-4 w-4" /> {{ setting('phone') }}</a>@endif
                </div>
            </div>
        </div>
        @if($product->content)
            <div class="container-x mt-14 max-w-4xl"><h2 class="section-title mb-6 !text-2xl">Ürün Açıklaması</h2><div class="prose-cms">{!! $product->content !!}</div></div>
        @endif
    </section>
    @if($related->isNotEmpty())
        <section class="section bg-sand !pt-14"><div class="container-x"><h2 class="section-title mb-8 !text-2xl">Benzer Ürünler</h2><div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">@foreach($related as $item)<x-product-card :product="$item" />@endforeach</div></div></section>
    @endif
@endsection
