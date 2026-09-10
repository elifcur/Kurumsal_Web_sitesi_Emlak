@extends('template::layouts.app')

@section('content')
    {{-- Hero: tam genişlik fotoğraf, soldan koyu yeşil gradient, beyaz serif başlık --}}
    <section class="relative min-h-[560px] overflow-hidden bg-primary-dark text-white md:min-h-[600px]">
        @if(setting('hero_image'))
            <img src="{{ upload_url(setting('hero_image')) }}" alt="" class="absolute inset-0 h-full w-full object-cover object-[right_center]" fetchpriority="high">
        @else
            <div class="absolute inset-0 bg-gradient-to-br from-primary via-primary-dark to-ink"></div>
        @endif
        <div class="hero-overlay absolute inset-0"></div>

        <div class="container-x relative flex min-h-[560px] flex-col justify-center pt-16 pb-44 md:min-h-[600px] md:pb-48">
            <div class="max-w-2xl">
                @if(setting('site_tagline'))<span class="eyebrow eyebrow-accent !mb-5">{{ setting('site_tagline') }}</span>@endif
                <h1 class="text-5xl leading-[1.05] md:text-6xl lg:text-7xl">{!! emphasize(setting('hero_title', 'Size en uygun gayrimenkulü *birlikte* bulalım')) !!}</h1>
                @if(setting('hero_subtitle'))<p class="mt-6 max-w-xl text-lg leading-8 text-white/85">{{ setting('hero_subtitle') }}</p>@endif
                <div class="mt-8 flex flex-wrap items-center gap-6">
                    @if(setting('hero_button_text'))<a href="{{ setting('hero_button_url', '/ilanlar') }}" class="btn-primary !px-6 !py-3.5 !text-base">{{ setting('hero_button_text') }} <x-icon name="arrow-right" class="h-4 w-4" /></a>@endif
                    @if(setting('hero_button2_text'))<a href="{{ setting('hero_button2_url', '/hizmetler') }}" class="inline-flex items-center gap-2.5 text-sm font-semibold text-white hover:text-accent"><span class="flex h-8 w-8 items-center justify-center rounded-full border border-accent/80 text-accent"><x-icon name="shield" class="h-4 w-4" /></span> {{ setting('hero_button2_text') }}</a>@endif
                </div>
            </div>
        </div>

        {{-- Sağ altta yüzen bilgi kartı --}}
        @if(setting('hero_card_title'))
            <div class="absolute right-0 bottom-52 hidden items-center gap-4 rounded-l-lg bg-primary-dark/90 py-6 pr-8 pl-7 shadow-xl backdrop-blur lg:flex">
                <span class="text-accent"><x-icon name="home" class="h-10 w-10" /></span>
                <span>
                    <span class="block text-sm font-semibold text-white">{{ setting('hero_card_title') }}</span>
                    @if(setting('hero_card_text'))<span class="block text-sm font-semibold text-white">{{ setting('hero_card_text') }}</span>@endif
                    <span class="mt-2 block h-0.5 w-10 bg-accent"></span>
                </span>
            </div>
        @endif
    </section>

    {{-- Hero'nun altına binen arama kartı --}}
    <section class="relative z-10">
        <div class="container-x -mt-32 md:-mt-36">
            <form method="get" action="{{ route('listings.index') }}" class="rounded-lg bg-white shadow-2xl" x-data="{ tur: 'sale' }">
                <div class="inline-flex flex-wrap gap-1 rounded-tl-lg rounded-br-lg bg-slate-100 p-1.5">
                    <button type="button" @click="tur = 'sale'" class="search-tab" :class="tur === 'sale' ? 'search-tab-active' : 'search-tab-idle'">Satılık</button>
                    <button type="button" @click="tur = 'rent'" class="search-tab" :class="tur === 'rent' ? 'search-tab-active' : 'search-tab-idle'">Kiralık</button>
                    <input type="hidden" name="tur" :value="tur">
                </div>
                <div class="grid gap-3 p-4 md:grid-cols-3 lg:grid-cols-6 lg:p-5">
                    <label class="relative block">
                        <span class="sr-only">İl</span>
                        <x-icon name="map-pin" class="pointer-events-none absolute top-1/2 left-3.5 h-4 w-4 -translate-y-1/2 text-slate-400" />
                        <select name="sehir" class="search-input appearance-none"><option value="">İl Seçin</option>@foreach($cities as $city)<option value="{{ $city }}">{{ $city }}</option>@endforeach</select>
                        <x-icon name="chevron-down" class="pointer-events-none absolute top-1/2 right-3.5 h-4 w-4 -translate-y-1/2 text-slate-400" />
                    </label>
                    <label class="relative block">
                        <span class="sr-only">İlçe</span>
                        <x-icon name="squares" class="pointer-events-none absolute top-1/2 left-3.5 h-4 w-4 -translate-y-1/2 text-slate-400" />
                        <input type="text" name="q" placeholder="İlçe" maxlength="100" class="search-input">
                    </label>
                    <label class="relative block">
                        <span class="sr-only">Emlak tipi</span>
                        <x-icon name="building" class="pointer-events-none absolute top-1/2 left-3.5 h-4 w-4 -translate-y-1/2 text-slate-400" />
                        <select name="tip" class="search-input appearance-none"><option value="">Emlak Tipi</option>@foreach(\Templates\RealEstate\Models\Listing::PROPERTY_TYPES as $k => $l)<option value="{{ $k }}">{{ $l }}</option>@endforeach</select>
                        <x-icon name="chevron-down" class="pointer-events-none absolute top-1/2 right-3.5 h-4 w-4 -translate-y-1/2 text-slate-400" />
                    </label>
                    <label class="relative block">
                        <span class="sr-only">Minimum fiyat</span>
                        <x-icon name="currency" class="pointer-events-none absolute top-1/2 left-3.5 h-4 w-4 -translate-y-1/2 text-slate-400" />
                        <input type="number" name="min_fiyat" placeholder="Min. Fiyat" min="0" class="search-input">
                    </label>
                    <label class="relative block">
                        <span class="sr-only">Maksimum fiyat</span>
                        <x-icon name="currency" class="pointer-events-none absolute top-1/2 left-3.5 h-4 w-4 -translate-y-1/2 text-slate-400" />
                        <input type="number" name="max_fiyat" placeholder="Max. Fiyat" min="0" class="search-input">
                    </label>
                    <button type="submit" class="btn-primary !py-3"><x-icon name="search" class="h-4 w-4" /> İlanları Ara</button>
                </div>
            </form>
        </div>
    </section>

    {{-- Öne çıkan ilanlar --}}
    @if($featuredListings->isNotEmpty())
        <section class="section">
            <div class="container-x">
                <x-section-heading eyebrow="Öne Çıkan İlanlar" :title="setting('listings_home_title', 'Sizin İçin Seçtiklerimiz')" :subtitle="setting('listings_home_intro')" :actionUrl="route('listings.index')" actionLabel="Tüm İlanları Gör" />
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach($featuredListings as $listing)<x-listing-card :listing="$listing" />@endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- İstatistikler + alıntı --}}
    @if($stats->isNotEmpty())
        <section class="border-y border-slate-200 bg-sand">
            <div class="container-x grid items-center gap-10 py-12 lg:grid-cols-5">
                <div class="grid grid-cols-2 gap-y-8 divide-slate-200 sm:grid-cols-4 sm:divide-x lg:col-span-3">
                    @foreach($stats as $stat)
                        <div class="px-4 text-center sm:px-6">
                            <span class="mx-auto mb-3 flex h-10 w-10 items-center justify-center rounded-full bg-primary-light text-primary"><x-icon :name="['calendar', 'home', 'key', 'star'][$loop->index % 4]" class="h-5 w-5" /></span>
                            <p class="font-display text-4xl text-ink">{{ $stat['value'] }}</p>
                            <p class="mt-1 text-xs font-medium tracking-wide text-slate-500 uppercase">{{ $stat['label'] }}</p>
                        </div>
                    @endforeach
                </div>
                <blockquote class="relative border-l border-slate-200 pl-8 lg:col-span-2">
                    <span class="absolute -top-4 left-6 font-display text-7xl leading-none text-accent">“</span>
                    <p class="font-display text-xl leading-8 text-ink italic md:text-2xl">{{ setting('about_quote', 'Amacımız sadece bir mülk satmak değil, sizin için doğru yatırımı bulmaktır.') }}</p>
                </blockquote>
            </div>
        </section>
    @endif

    {{-- Hakkımızda --}}
    @if(setting('about_text'))
        <section class="section">
            <div class="container-x grid items-center gap-12 lg:grid-cols-2">
                <div>
                    <span class="eyebrow">Biz Kimiz</span>
                    <h2 class="section-title">{!! emphasize(setting('about_title', 'Hakkımızda')) !!}</h2>
                    <span class="section-line"></span>
                    <div class="mt-6 space-y-4 text-lg leading-8 text-slate-600">
                        @foreach(preg_split('/\r\n|\r|\n/', setting('about_text')) as $paragraph)@if(trim($paragraph) !== '')<p>{{ $paragraph }}</p>@endif @endforeach
                    </div>
                    <ul class="mt-8 grid gap-3 sm:grid-cols-2">
                        @foreach(['Yerinde incelenmiş ilanlar', 'Şeffaf komisyon politikası', 'Tapu ve kredi takibi', 'Bölge uzmanı danışmanlar'] as $item)
                            <li class="flex items-center gap-2 text-sm font-medium text-ink"><x-icon name="check-circle" class="h-5 w-5 text-accent" /> {{ $item }}</li>
                        @endforeach
                    </ul>
                    <a href="{{ route('page.show', 'hakkimizda') }}" class="btn-primary mt-9">Daha Fazla Bilgi <x-icon name="arrow-right" class="h-4 w-4" /></a>
                </div>
                <div class="relative">
                    @if(setting('about_image') || setting('hero_image'))
                        <img src="{{ upload_url(setting('about_image', setting('hero_image'))) }}" alt="{{ setting('about_title', 'Hakkımızda') }}" class="aspect-[4/3] w-full rounded-lg object-cover shadow-xl" loading="lazy">
                    @else
                        <div class="flex aspect-[4/3] items-center justify-center rounded-lg border border-slate-200 bg-gradient-to-br from-white to-primary-light text-primary/40 shadow-sm"><x-icon name="building" class="h-24 w-24" /></div>
                    @endif
                    <div class="absolute -bottom-6 left-6 hidden items-center gap-3 rounded-lg bg-primary px-5 py-4 text-white shadow-lg md:flex">
                        <span class="text-accent"><x-icon name="shield" class="h-8 w-8" /></span>
                        <span class="text-sm font-semibold leading-tight">Güvenilir Çözüm<br>Ortağınız</span>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- Hizmetler --}}
    @if($services->isNotEmpty())
        <section class="section bg-sand">
            <div class="container-x">
                <x-section-heading eyebrow="Neler Yapıyoruz" :title="setting('services_label', 'Hizmetlerimiz')" :subtitle="setting('services_intro')" :actionUrl="route('services.index')" actionLabel="Tüm Hizmetleri Gör" />
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach($services as $service)<x-service-card :service="$service" />@endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Danışmanlar --}}
    @if($team->isNotEmpty())
        <section class="section">
            <div class="container-x">
                <x-section-heading eyebrow="Ekibimiz" :title="setting('team_title', 'Uzman Danışmanlarımız')" :actionUrl="route('team.index')" actionLabel="Tüm Ekibi Gör" />
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach($team as $member)<x-team-card :member="$member" />@endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- CTA --}}
    @if(setting('cta_title'))
        <section class="relative overflow-hidden bg-primary text-white">
            @if(setting('hero_image'))<img src="{{ upload_url(setting('hero_image')) }}" alt="" class="absolute inset-0 h-full w-full object-cover opacity-20" loading="lazy">@endif
            <div class="container-x relative flex flex-col items-center justify-between gap-6 py-16 text-center md:flex-row md:text-left">
                <div>
                    <h2 class="font-display text-3xl md:text-4xl">{!! emphasize(setting('cta_title')) !!}</h2>
                    <span class="mx-auto mt-4 block h-0.5 w-12 bg-accent md:mx-0"></span>
                    @if(setting('cta_text'))<p class="mt-4 text-lg text-white/80">{{ setting('cta_text') }}</p>@endif
                </div>
                @if(setting('cta_button_text'))<a href="{{ setting('cta_button_url', route('contact.show')) }}" class="btn-accent shrink-0 !px-7 !py-3.5">{{ setting('cta_button_text') }} <x-icon name="arrow-right" class="h-4 w-4" /></a>@endif
            </div>
        </section>
    @endif

    {{-- Yorumlar + SSS --}}
    @if($testimonials->isNotEmpty() || $faqs->isNotEmpty())
        <section class="section bg-sand">
            <div class="container-x grid gap-16 lg:grid-cols-2">
                @if($testimonials->isNotEmpty())
                    <div>
                        <span class="eyebrow">Müşterilerimiz</span>
                        <h2 class="section-title !text-4xl">{!! emphasize('Ne *diyorlar*?') !!}</h2>
                        <span class="section-line"></span>
                        <div class="mt-8 grid gap-5">@foreach($testimonials as $testimonial)<x-testimonial-card :testimonial="$testimonial" />@endforeach</div>
                    </div>
                @endif
                @if($faqs->isNotEmpty())
                    <div>
                        <span class="eyebrow">Merak Edilenler</span>
                        <h2 class="section-title !text-4xl">Sık Sorulan Sorular</h2>
                        <span class="section-line"></span>
                        <div class="mt-8"><x-faq-list :faqs="$faqs" /></div>
                    </div>
                @endif
            </div>
        </section>
    @endif

    {{-- Blog --}}
    @if($posts->isNotEmpty())
        <section class="section">
            <div class="container-x">
                <x-section-heading eyebrow="Emlak Rehberi" :title="setting('blog_label', 'Blog')" subtitle="Alım, satım ve kiralama hakkında güncel yazılar." :actionUrl="route('blog.index')" actionLabel="Tüm Yazıları Gör" />
                <div class="grid gap-6 md:grid-cols-3">@foreach($posts as $post)<x-post-card :post="$post" />@endforeach</div>
            </div>
        </section>
    @endif

    {{-- Partner / referans logoları --}}
    @if($partners->isNotEmpty())
        <section class="border-t border-slate-200 bg-white py-14">
            <div class="container-x">
                <p class="eyebrow text-center">Yetkili Satış Ortağı Olduğumuz Projeler</p>
                <x-partner-strip :partners="$partners" class="mt-8" />
            </div>
        </section>
    @endif
@endsection
