@props(['listing'])
<a href="{{ $listing->url() }}" class="card group flex flex-col overflow-hidden">
    <div class="relative aspect-[2/1] overflow-hidden bg-slate-100">
        @if($listing->image)<img src="{{ upload_url($listing->image) }}" alt="{{ $listing->title }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-105" loading="lazy">@else<div class="flex h-full items-center justify-center bg-gradient-to-br from-primary-light to-sand text-primary/40"><x-icon name="home" class="h-12 w-12" /></div>@endif
        <span class="absolute top-3 left-3 {{ $listing->listing_type === 'sale' ? 'badge-sale' : 'badge-rent' }}">{{ $listing->typeLabel() }}</span>
        <span class="absolute top-3 right-3 flex h-8 w-8 items-center justify-center rounded-full bg-white/15 text-white backdrop-blur transition group-hover:bg-white group-hover:text-primary" aria-hidden="true"><x-icon name="heart" class="h-4 w-4" /></span>
    </div>
    <div class="flex flex-1 flex-col p-4">
        @if($listing->location())<span class="flex items-center gap-1 text-xs text-slate-500"><x-icon name="map-pin" class="h-3.5 w-3.5 text-slate-400" /> {{ $listing->location() }}</span>@endif
        <h3 class="mt-1.5 font-semibold text-ink group-hover:text-primary">{{ $listing->title }}</h3>
        <div class="mt-3 flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-600">
            @if($listing->rooms)<span class="flex items-center gap-1"><x-icon name="home" class="h-3.5 w-3.5 text-slate-400" /> {{ $listing->rooms }}</span>@endif
            @if($listing->area_gross)<span class="flex items-center gap-1"><x-icon name="squares" class="h-3.5 w-3.5 text-slate-400" /> {{ $listing->area_gross }} m²</span>@endif
            <span class="flex items-center gap-1"><x-icon name="building" class="h-3.5 w-3.5 text-slate-400" /> {{ $listing->propertyTypeLabel() }}</span>
        </div>
        @if($listing->displayPrice())<p class="mt-4 text-lg font-bold text-ink">{{ $listing->displayPrice() }}</p>@endif
    </div>
</a>
