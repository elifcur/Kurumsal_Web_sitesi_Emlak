@extends('template::layouts.app')
@section('seo')<x-seo :title="$page->meta_title ?: $page->title" :description="$page->meta_description ?: $page->excerpt" :image="$page->image" type="article" />@endsection
@section('content')
    <x-page-header :title="$page->title" :subtitle="$page->excerpt" :image="$page->template === 'wide' ? null : $page->image" :breadcrumbs="[['label' => $page->title]]" />
    <section class="section">
        <div @class(['container-x', 'max-w-4xl' => $page->template !== 'wide'])>
            @if($page->template === 'wide' && $page->image)<img src="{{ upload_url($page->image) }}" alt="{{ $page->title }}" class="mb-10 w-full rounded-lg object-cover">@endif
            <div class="prose-cms">{!! $page->content !!}</div>
            @if($page->template === 'contact')
                <div class="mt-12 rounded-lg border border-slate-200 p-6 md:p-8">
                    @if(session('success'))<x-alert type="success" class="mb-6">{{ session('success') }}</x-alert>@endif
                    <x-contact-form />
                </div>
            @endif
        </div>
    </section>
@endsection
