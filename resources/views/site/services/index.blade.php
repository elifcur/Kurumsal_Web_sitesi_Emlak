@extends('template::layouts.app')
@section('seo')<x-seo :title="setting('services_label', 'Hizmetlerimiz')" :description="setting('services_intro')" />@endsection
@section('content')
    <x-page-header :title="setting('services_label', 'Hizmetlerimiz')" :subtitle="setting('services_intro')" :breadcrumbs="[['label' => setting('services_label', 'Hizmetlerimiz')]]" />
    <section class="section">
        <div class="container-x">
            @if($services->isEmpty())
                <x-empty-state icon="briefcase" title="Henüz hizmet eklenmedi" />
            @else
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">@foreach($services as $service)<x-service-card :service="$service" />@endforeach</div>
                <div class="mt-10">{{ $services->links() }}</div>
            @endif
        </div>
    </section>
    @if(setting('cta_title'))
        <section class="bg-primary text-primary-contrast"><div class="container-x flex flex-col items-center justify-between gap-6 py-14 text-center md:flex-row md:text-left"><div><h2 class="font-display text-2xl font-bold md:text-3xl">{{ setting('cta_title') }}</h2>@if(setting('cta_text'))<p class="mt-2 text-primary-contrast/80">{{ setting('cta_text') }}</p>@endif</div>@if(setting('cta_button_text'))<a href="{{ setting('cta_button_url', route('contact.show')) }}" class="btn-light shrink-0">{{ setting('cta_button_text') }}</a>@endif</div></section>
    @endif
@endsection
