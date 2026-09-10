@extends('template::layouts.app')
@section('seo')<x-seo :title="$listing->meta_title ?: $listing->title" :description="$listing->meta_description ?: \Illuminate\Support\Str::limit($listing->description, 160)" :image="$listing->image" type="article" />@endsection
@section('content')
    @push('head')
        <script type="application/ld+json">{!! json_encode(array_filter(['@context' => 'https://schema.org', '@type' => 'RealEstateListing', 'name' => $listing->title, 'url' => $listing->url(), 'description' => \Illuminate\Support\Str::limit($listing->description, 300), 'image' => upload_url($listing->image), 'offers' => $listing->price ? ['@type' => 'Offer', 'price' => (float) $listing->price, 'priceCurrency' => $listing->currency] : null]), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endpush
    <x-page-header :title="$listing->title" :subtitle="$listing->typeLabel().' '.$listing->propertyTypeLabel().($listing->location() ? ' · '.$listing->location() : '')" :breadcrumbs="[['label' => setting('listings_label', 'İlanlar'), 'url' => route('listings.index')], ['label' => \Illuminate\Support\Str::limit($listing->title, 40)]]" />

    <section class="section !pt-10" x-data="gallery(@js(array_map(fn ($p) => upload_url($p), $listing->allImages())))">
        <div class="container-x grid gap-12 lg:grid-cols-3">
            <div class="space-y-8 lg:col-span-2">
                <div>
                    <div class="relative aspect-[16/10] overflow-hidden rounded-lg border border-slate-200 bg-sand">
                        <template x-if="current"><img :src="current" alt="{{ $listing->title }}" class="h-full w-full object-cover"></template>
                        <template x-if="!current"><div class="flex h-full items-center justify-center text-primary/30"><x-icon name="home" class="h-20 w-20" /></div></template>
                        <span class="absolute top-4 left-4 {{ $listing->listing_type === 'sale' ? 'badge-sale' : 'badge-rent' }}">{{ $listing->typeLabel() }}</span>
                        <template x-if="images.length > 1">
                            <div>
                                <button type="button" @click="prev()" class="absolute top-1/2 left-3 -translate-y-1/2 rounded-full bg-white/90 p-2 text-ink shadow hover:bg-white" aria-label="Önceki"><x-icon name="chevron-left" class="h-5 w-5" /></button>
                                <button type="button" @click="next()" class="absolute top-1/2 right-3 -translate-y-1/2 rounded-full bg-white/90 p-2 text-ink shadow hover:bg-white" aria-label="Sonraki"><x-icon name="chevron-right" class="h-5 w-5" /></button>
                                <span class="absolute right-3 bottom-3 rounded-full bg-ink/80 px-2.5 py-1 text-xs text-white" x-text="(index + 1) + ' / ' + images.length"></span>
                            </div>
                        </template>
                    </div>
                    <template x-if="images.length > 1">
                        <div class="mt-4 grid grid-cols-4 gap-3 sm:grid-cols-6">
                            <template x-for="(img, i) in images" :key="i">
                                <button type="button" @click="index = i" class="aspect-square overflow-hidden rounded-lg border-2" :class="index === i ? 'border-primary' : 'border-transparent'"><img :src="img" alt="" class="h-full w-full object-cover" loading="lazy"></button>
                            </template>
                        </div>
                    </template>
                </div>

                <dl class="grid grid-cols-2 gap-4 rounded-lg border border-slate-200 bg-white p-6 text-sm sm:grid-cols-4">
                    @foreach([['Oda', $listing->rooms], ['Brüt m²', $listing->area_gross], ['Net m²', $listing->area_net], ['Kat', $listing->floor], ['Banyo', $listing->bathrooms], ['Bina Yaşı', $listing->building_age], ['Isıtma', $listing->heating], ['Eşyalı', $listing->furnished ? 'Evet' : 'Hayır'], ['Tip', $listing->propertyTypeLabel()], ['Konum', $listing->location() ?: null]] as [$label, $value])
                        @if($value !== null && $value !== '')<div><dt class="text-xs text-slate-500">{{ $label }}</dt><dd class="font-semibold text-ink">{{ $value }}</dd></div>@endif
                    @endforeach
                </dl>

                @if($listing->description)<div><h2 class="mb-3 font-display text-xl font-bold text-ink">Açıklama</h2><div class="prose-cms whitespace-pre-line">{{ $listing->description }}</div></div>@endif
                @if($listing->features)<div><h2 class="mb-3 font-display text-xl font-bold text-ink">Özellikler</h2><ul class="grid grid-cols-2 gap-2 text-sm sm:grid-cols-3">@foreach($listing->features as $feature)<li class="flex items-center gap-2 text-slate-700"><x-icon name="check-circle" class="h-4 w-4 text-primary" /> {{ $feature }}</li>@endforeach</ul></div>@endif
                @if($listing->video_url)
                    @php($embed = preg_replace(['#youtu\.be/([\w-]+)#', '#youtube\.com/watch\?v=([\w-]+)#', '#vimeo\.com/(\d+)#'], ['www.youtube.com/embed/$1', 'www.youtube.com/embed/$1', 'player.vimeo.com/video/$1'], $listing->video_url))
                    <div><h2 class="mb-3 font-display text-xl font-bold text-ink">Video</h2><div class="overflow-hidden rounded-lg border border-slate-200"><iframe src="{{ $embed }}" class="aspect-video w-full border-0" loading="lazy" allowfullscreen title="İlan videosu"></iframe></div></div>
                @endif
                @if($listing->map_embed)<div><h2 class="mb-3 font-display text-xl font-bold text-ink">Konum</h2><div class="overflow-hidden rounded-lg border border-slate-200"><iframe src="{{ $listing->map_embed }}" class="h-80 w-full border-0" loading="lazy" title="Konum"></iframe></div>@if($listing->address)<p class="mt-2 text-sm text-slate-500">{{ $listing->address }}</p>@endif</div>@endif
            </div>

            <aside>
                <div class="sticky top-24 space-y-5">
                    <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                        @if($listing->formattedPrice())<p class="font-display text-3xl font-extrabold text-ink">{{ $listing->formattedPrice() }}</p>@endif
                        @if($listing->agent)
                            <div class="mt-5 flex items-center gap-3 rounded-md bg-sand p-3">
                                <span class="flex h-12 w-12 shrink-0 items-center justify-center overflow-hidden rounded-full bg-primary-light text-primary">@if($listing->agent->image)<img src="{{ upload_url($listing->agent->image) }}" alt="" class="h-full w-full object-cover">@else<x-icon name="user" class="h-6 w-6" />@endif</span>
                                <span><a href="{{ $listing->agent->url() }}" class="block text-sm font-semibold text-ink hover:text-primary">{{ $listing->agent->name }}</a><span class="block text-xs text-slate-500">{{ $listing->agent->title }}</span></span>
                            </div>
                            @if($listing->agent->phone)<a href="{{ phone_href($listing->agent->phone) }}" class="btn-outline mt-3 w-full"><x-icon name="phone" class="h-4 w-4" /> {{ $listing->agent->phone }}</a>@endif
                        @elseif(setting('phone'))
                            <a href="{{ phone_href(setting('phone')) }}" class="btn-outline mt-4 w-full"><x-icon name="phone" class="h-4 w-4" /> {{ setting('phone') }}</a>
                        @endif
                    </div>
                    <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="font-display text-lg font-bold text-ink">Bilgi / Randevu İsteyin</h2>
                        @if(session('success'))<x-alert type="success" class="mt-4">{{ session('success') }}</x-alert>@endif
                        <form method="post" action="{{ route('listings.inquiry', $listing->slug) }}" class="mt-4 space-y-3">
                            @csrf
                            <div class="hidden" aria-hidden="true"><input type="text" name="website" tabindex="-1" autocomplete="off"></div>
                            <input name="name" value="{{ old('name') }}" placeholder="Ad Soyad *" required class="form-input">@error('name')<p class="form-error">{{ $message }}</p>@enderror
                            <input name="email" type="email" value="{{ old('email') }}" placeholder="E-posta *" required class="form-input">@error('email')<p class="form-error">{{ $message }}</p>@enderror
                            <input name="phone" type="tel" value="{{ old('phone') }}" placeholder="Telefon *" required class="form-input">@error('phone')<p class="form-error">{{ $message }}</p>@enderror
                            <textarea name="message" rows="3" placeholder="Mesajınız" class="form-input">{{ old('message') }}</textarea>
                            <label class="flex items-start gap-2 text-xs text-slate-600"><input type="checkbox" name="kvkk" value="1" class="mt-0.5"> <span><a href="{{ route('page.show', 'kvkk') }}" target="_blank" class="text-primary underline">KVKK metnini</a> okudum, onaylıyorum.</span></label>@error('kvkk')<p class="form-error">{{ $message }}</p>@enderror
                            <button type="submit" class="btn-primary w-full">Gönder</button>
                        </form>
                    </div>
                </div>
            </aside>
        </div>
    </section>

    @if($similar->isNotEmpty())
        <section class="section bg-sand !pt-14"><div class="container-x"><h2 class="section-title mb-8 !text-2xl">Benzer İlanlar</h2><div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">@foreach($similar as $item)<x-listing-card :listing="$item" />@endforeach</div></div></section>
    @endif
@endsection
