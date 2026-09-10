@extends('template::layouts.app')
@section('seo')<x-seo :title="setting('listings_label', 'İlanlar')" description="Satılık ve kiralık gayrimenkul ilanlarımızı inceleyin." :robots="request()->query() ? 'noindex, follow' : 'index, follow'" />@endsection
@section('content')
    <x-page-header :title="setting('listings_label', 'İlanlar')" subtitle="Satılık ve kiralık portföyümüzü filtreleyerek inceleyin." :breadcrumbs="[['label' => setting('listings_label', 'İlanlar')]]" />
    <section class="section !pt-10">
        <div class="container-x grid gap-8 lg:grid-cols-4">
            <aside class="lg:col-span-1">
                <form method="get" class="sticky top-24 space-y-3 rounded-lg border border-slate-200 bg-sand p-5" x-data="listingFilters">
                    <h3 class="font-semibold text-ink">Filtrele</h3>
                    <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Başlık, ilçe, şehir..." class="form-input">
                    <select name="tur" class="form-input" @change="submit()"><option value="">Satılık / Kiralık</option>@foreach(\Templates\RealEstate\Models\Listing::TYPES as $k => $l)<option value="{{ $k }}" @selected(($filters['tur'] ?? '') === $k)>{{ $l }}</option>@endforeach</select>
                    <select name="tip" class="form-input" @change="submit()"><option value="">Tüm tipler</option>@foreach(\Templates\RealEstate\Models\Listing::PROPERTY_TYPES as $k => $l)<option value="{{ $k }}" @selected(($filters['tip'] ?? '') === $k)>{{ $l }}</option>@endforeach</select>
                    <select name="sehir" class="form-input" @change="submit()"><option value="">Tüm şehirler</option>@foreach($cities as $city)<option value="{{ $city }}" @selected(($filters['sehir'] ?? '') === $city)>{{ $city }}</option>@endforeach</select>
                    <select name="oda" class="form-input" @change="submit()"><option value="">Oda sayısı</option>@foreach($roomOptions as $room)<option value="{{ $room }}" @selected(($filters['oda'] ?? '') === $room)>{{ $room }}</option>@endforeach</select>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="number" name="min_fiyat" value="{{ $filters['min_fiyat'] ?? '' }}" placeholder="Min ₺" class="form-input" min="0">
                        <input type="number" name="max_fiyat" value="{{ $filters['max_fiyat'] ?? '' }}" placeholder="Max ₺" class="form-input" min="0">
                    </div>
                    <select name="sirala" class="form-input" @change="submit()">
                        <option value="yeni" @selected(($filters['sirala'] ?? 'yeni') === 'yeni')>Öne çıkan / Yeni</option>
                        <option value="fiyat-artan" @selected(($filters['sirala'] ?? '') === 'fiyat-artan')>Fiyat: artan</option>
                        <option value="fiyat-azalan" @selected(($filters['sirala'] ?? '') === 'fiyat-azalan')>Fiyat: azalan</option>
                    </select>
                    <button type="submit" class="btn-primary w-full"><x-icon name="filter" class="h-4 w-4" /> Uygula</button>
                    @if(request()->query())<a href="{{ route('listings.index') }}" class="block text-center text-xs font-medium text-slate-500 hover:text-primary">Filtreleri temizle</a>@endif
                </form>
            </aside>
            <div class="lg:col-span-3">
                @if($listings->isEmpty())
                    <x-empty-state icon="home" title="Kriterlere uygun ilan bulunamadı" text="Filtreleri değiştirerek tekrar deneyin." />
                @else
                    <p class="mb-4 text-sm text-slate-500"><strong class="text-ink">{{ $listings->total() }}</strong> ilan bulundu</p>
                    <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">@foreach($listings as $listing)<x-listing-card :listing="$listing" />@endforeach</div>
                    <div class="mt-10">{{ $listings->links() }}</div>
                @endif
            </div>
        </div>
    </section>
@endsection
