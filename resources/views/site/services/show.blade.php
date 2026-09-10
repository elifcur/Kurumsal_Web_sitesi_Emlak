@extends('template::layouts.app')
@section('seo')<x-seo :title="$service->meta_title ?: $service->title" :description="$service->meta_description ?: $service->excerpt" :image="$service->image" />@endsection
@section('content')
    <x-page-header :title="$service->title" :subtitle="$service->excerpt" :image="$service->image" :breadcrumbs="[['label' => setting('services_label', 'Hizmetlerimiz'), 'url' => route('services.index')], ['label' => $service->title]]" />
    <section class="section">
        <div class="container-x grid gap-12 lg:grid-cols-3">
            <div class="lg:col-span-2">
                @if($service->image)<img src="{{ upload_url($service->image) }}" alt="{{ $service->title }}" class="mb-8 w-full rounded-lg object-cover">@endif
                <div class="prose-cms">{!! $service->content !!}</div>
            </div>
            <aside class="space-y-6">
                @if($others->isNotEmpty())
                    <div class="rounded-lg border border-slate-200 p-6">
                        <h3 class="font-display font-semibold text-ink">Diğer {{ setting('services_label', 'Hizmetler') }}</h3>
                        <ul class="mt-4 divide-y divide-slate-100">@foreach($others as $other)<li><a href="{{ $other->url() }}" class="flex items-center gap-3 py-3 text-sm text-slate-700 hover:text-primary"><x-icon :name="$other->icon ?: 'chevron-right'" class="h-4 w-4 text-primary" /> {{ $other->title }}</a></li>@endforeach</ul>
                    </div>
                @endif
                <div class="rounded-lg bg-ink p-6 text-white">
                    <h3 class="font-display text-lg font-bold">Bilgi almak ister misiniz?</h3>
                    <p class="mt-2 text-sm text-slate-300">Ekibimiz sorularınızı yanıtlamak için hazır.</p>
                    <a href="{{ route('contact.show', ['konu' => 'Bilgi almak istiyorum']) }}" class="btn-primary mt-5 w-full">İletişime Geçin</a>
                    @if(setting('phone'))<a href="{{ phone_href(setting('phone')) }}" class="mt-3 flex items-center justify-center gap-2 text-sm font-semibold"><x-icon name="phone" class="h-4 w-4" /> {{ setting('phone') }}</a>@endif
                </div>
            </aside>
        </div>
    </section>
@endsection
