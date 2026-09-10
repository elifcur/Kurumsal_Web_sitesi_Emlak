@props(['product'])
<a href="{{ $product->url() }}" class="card group flex flex-col overflow-hidden">
    <div class="aspect-[4/3] overflow-hidden bg-sand">
        @if($product->image)<img src="{{ upload_url($product->image) }}" alt="{{ $product->title }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-105" loading="lazy">@else<div class="flex h-full items-center justify-center text-primary/30"><x-icon name="box" class="h-12 w-12" /></div>@endif
    </div>
    <div class="flex flex-1 flex-col p-5">
        @if($product->category)<span class="text-xs font-medium text-primary">{{ $product->category->name }}</span>@endif
        <h3 class="mt-1 font-semibold text-ink group-hover:text-primary">{{ $product->title }}</h3>
        @if($product->excerpt)<p class="mt-2 line-clamp-2 text-sm leading-6 text-slate-600">{{ $product->excerpt }}</p>@endif
        <div class="mt-4 flex items-center justify-between">
            @if($product->formattedPrice())<span class="text-lg font-bold text-ink">{{ $product->formattedPrice() }}</span>@else<span></span>@endif
            <span class="inline-flex items-center gap-1 text-sm font-medium text-ink">İncele <x-icon name="arrow-right" class="h-4 w-4 text-accent transition group-hover:translate-x-1" /></span>
        </div>
    </div>
</a>
