@extends('template::layouts.app')

@section('content')
    {{-- Hero --}}
    <section class="relative overflow-hidden bg-primary-dark text-white">
        @if(setting('hero_image'))
            <img src="{{ upload_url(setting('hero_image')) }}" alt="" class="absolute inset-0 h-full w-full object-cover object-[right_center]" fetchpriority="high">
        @else
            <div class="absolute inset-0 bg-gradient-to-br from-primary via-primary-dark to-ink"></div>
        @endif
        <div class="hero-overlay absolute inset-0"></div>
        <div class="container-x relative flex min-h-[520px] flex-col justify-center py-20 md:min-h-[600px]">
            <div class="max-w-2xl">
                @if(setting('site_tagline'))<span class="eyebrow eyebrow-accent !mb-5">{{ setting('site_tagline') }}</span>@endif
                <h1 class="text-5xl leading-[1.05] md:text-6xl lg:text-7xl">{!! emphasize(setting('hero_title', 'İşinizi büyütecek *kurumsal* çözümler')) !!}</h1>
                @if(setting('hero_subtitle'))<p class="mt-6 max-w-xl text-lg leading-8 text-white/85">{{ setting('hero_subtitle') }}</p>@endif
                <div class="mt-8 flex flex-wrap items-center gap-4">
                    @if(setting('hero_button_text'))<a href="{{ setting('hero_button_url', route('contact.show')) }}" class="btn-accent !px-6 !py-3.5 !text-base">{{ setting('hero_button_text') }} <x-icon name="arrow-right" class="h-4 w-4" /></a>@endif
                    @if(setting('hero_button2_text'))<a href="{{ setting('hero_button2_url', route('contact.show')) }}" class="btn-outline-light !px-6 !py-3.5 !text-base">{{ setting('hero_button2_text') }}</a>@endif
                </div>
            </div>
        </div>
    </section>

    {{-- Partner / referans logoları --}}
    @if($partners->isNotEmpty())
        <section class="border-b border-slate-200 bg-white py-12">
            <div class="container-x">
                <p class="eyebrow text-center">Birlikte Çalıştığımız Markalar</p>
                <x-partner-strip :partners="$partners" class="mt-8" />
            </div>
        </section>
    @endif

    {{-- Hizmetler --}}
    @if($services->isNotEmpty())
        <section class="section">
            <div class="container-x">
                <x-section-heading eyebrow="Neler Yapıyoruz" :title="setting('services_label', 'Hizmetlerimiz')" :subtitle="setting('services_intro')" :actionUrl="route('services.index')" actionLabel="Tüm Hizmetleri Gör" />
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($services as $service)<x-service-card :service="$service" />@endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Hakkımızda --}}
    @if(setting('about_text'))
        <section class="section bg-sand">
            <div class="container-x grid items-center gap-12 lg:grid-cols-2">
                <div class="relative order-2 lg:order-1">
                    @if(setting('about_image') || setting('hero_image'))
                        <img src="{{ upload_url(setting('about_image', setting('hero_image'))) }}" alt="{{ setting('about_title', 'Hakkımızda') }}" class="aspect-[4/3] w-full rounded-lg object-cover shadow-xl" loading="lazy">
                    @else
                        <div class="flex aspect-[4/3] items-center justify-center rounded-lg border border-slate-200 bg-gradient-to-br from-white to-primary-light text-primary/40 shadow-sm"><x-icon :name="$template->icon()" class="h-24 w-24" /></div>
                    @endif
                </div>
                <div class="order-1 lg:order-2">
                    <span class="eyebrow">Biz Kimiz</span>
                    <h2 class="section-title">{!! emphasize(setting('about_title', 'Hakkımızda')) !!}</h2>
                    <span class="section-line"></span>
                    <div class="mt-6 space-y-4 text-lg leading-8 text-slate-600">
                        @foreach(preg_split('/\r\n|\r|\n/', setting('about_text')) as $paragraph)@if(trim($paragraph) !== '')<p>{{ $paragraph }}</p>@endif @endforeach
                    </div>
                    @if($aboutPoints->isNotEmpty())
                        <ul class="mt-8 grid gap-3 sm:grid-cols-2">
                            @foreach($aboutPoints as $point)<li class="flex items-center gap-2 text-sm font-medium text-ink"><x-icon name="check-circle" class="h-5 w-5 text-accent" /> {{ $point }}</li>@endforeach
                        </ul>
                    @endif
                    <a href="{{ route('page.show', 'hakkimizda') }}" class="btn-primary mt-9">Daha Fazla Bilgi <x-icon name="arrow-right" class="h-4 w-4" /></a>
                </div>
            </div>
        </section>
    @endif

    {{-- İstatistikler + alıntı --}}
    @if($stats->isNotEmpty())
        <section class="border-y border-slate-200 bg-white">
            <div class="container-x grid items-center gap-10 py-12 lg:grid-cols-5">
                <div @class(['grid grid-cols-2 gap-y-8 divide-slate-200 sm:grid-cols-4 sm:divide-x', 'lg:col-span-3' => setting('about_quote'), 'lg:col-span-5' => ! setting('about_quote')])>
                    @foreach($stats as $stat)
                        <div class="px-4 text-center sm:px-6">
                            <span class="mx-auto mb-3 flex h-10 w-10 items-center justify-center rounded-full bg-primary-light text-primary"><x-icon :name="['calendar', 'users', 'check-circle', 'star'][$loop->index % 4]" class="h-5 w-5" /></span>
                            <p class="font-display text-4xl text-ink">{{ $stat['value'] }}</p>
                            <p class="mt-1 text-xs font-medium tracking-wide text-slate-500 uppercase">{{ $stat['label'] }}</p>
                        </div>
                    @endforeach
                </div>
                @if(setting('about_quote'))
                    <blockquote class="relative border-l border-slate-200 pl-8 lg:col-span-2">
                        <span class="absolute -top-4 left-6 font-display text-7xl leading-none text-accent">“</span>
                        <p class="font-display text-xl leading-8 text-ink italic md:text-2xl">{{ setting('about_quote') }}</p>
                    </blockquote>
                @endif
            </div>
        </section>
    @endif

    {{-- Öne çıkan ürünler --}}
    @if($products->isNotEmpty())
        <section class="section">
            <div class="container-x">
                <x-section-heading eyebrow="Öne Çıkanlar" :title="setting('products_label', 'Ürünler')" :subtitle="setting('products_intro')" :actionUrl="route('products.index')" actionLabel="Tüm Ürünleri Gör" />
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach($products as $product)<x-product-card :product="$product" />@endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Ekip --}}
    @if($team->isNotEmpty())
        <section class="section bg-sand">
            <div class="container-x">
                <x-section-heading eyebrow="Ekibimiz" :title="setting('team_title', 'Ekibimiz')" :subtitle="setting('team_intro')" :actionUrl="route('team.index')" actionLabel="Tüm Ekibi Gör" />
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
        <section class="section">
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
        <section class="section bg-sand">
            <div class="container-x">
                <x-section-heading eyebrow="Güncel" :title="setting('blog_label', 'Blog')" :subtitle="setting('blog_intro')" :actionUrl="route('blog.index')" actionLabel="Tüm Yazıları Gör" />
                <div class="grid gap-6 md:grid-cols-3">@foreach($posts as $post)<x-post-card :post="$post" />@endforeach</div>
            </div>
        </section>
    @endif
@endsection
