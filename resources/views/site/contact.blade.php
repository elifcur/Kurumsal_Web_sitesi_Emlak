@extends('template::layouts.app')
@section('seo')<x-seo title="İletişim" :description="'Bize ulaşın: '.setting('phone').' - '.setting('email')" />@endsection
@section('content')
    <x-page-header title="İletişim" :subtitle="setting('contact_intro')" :breadcrumbs="[['label' => 'İletişim']]" />
    <section class="section">
        <div class="container-x grid gap-12 lg:grid-cols-5">
            <div class="space-y-4 lg:col-span-2">
                @foreach([['map-pin', 'Adres', setting('address'), null], ['phone', 'Telefon', setting('phone'), phone_href(setting('phone'))], ['mail', 'E-posta', setting('email'), 'mailto:'.setting('email')], ['clock', 'Çalışma Saatleri', setting('working_hours'), null]] as [$icon, $label, $value, $href])
                    @if($value)
                        <div class="flex gap-4 rounded-lg border border-slate-200 p-5"><span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-md bg-primary-light text-primary"><x-icon :name="$icon" class="h-5 w-5" /></span><div><p class="text-sm font-semibold text-ink">{{ $label }}</p>@if($href)<a href="{{ $href }}" class="mt-1 block text-sm text-slate-600 hover:text-primary">{{ $value }}</a>@else<p class="mt-1 text-sm text-slate-600">{{ $value }}</p>@endif</div></div>
                    @endif
                @endforeach
            </div>
            <div class="lg:col-span-3">
                <div class="rounded-lg border border-slate-200 p-6 shadow-sm md:p-8">
                    <h2 class="font-display text-xl font-bold text-ink">Bize Yazın</h2>
                    <p class="mt-1 mb-6 text-sm text-slate-500">Formu doldurun, ekibimiz en kısa sürede size dönüş yapsın.</p>
                    @if(session('success'))<x-alert type="success" class="mb-6">{{ session('success') }}</x-alert>@endif
                    <x-contact-form />
                </div>
            </div>
        </div>
    </section>
    @if(setting('map_embed'))<section class="h-96 w-full"><iframe src="{{ setting('map_embed') }}" class="h-full w-full border-0" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen title="Harita"></iframe></section>@endif
@endsection
