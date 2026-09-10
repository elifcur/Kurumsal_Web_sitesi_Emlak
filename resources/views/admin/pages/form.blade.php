@extends('layouts.admin')
@section('title', $page->exists ? 'Sayfa Düzenle' : 'Yeni Sayfa')
@section('content')
    <x-admin.page-header :title="$page->exists ? 'Sayfa Düzenle' : 'Yeni Sayfa'" :subtitle="$page->exists ? $page->title : null" />

    <form method="post" action="{{ $page->exists ? route('admin.pages.update', $page) : route('admin.pages.store') }}" x-data="slugField(@js(old('slug', $page->slug ?? '')), {{ $page->exists ? 'true' : 'false' }})">
        @csrf
        @if($page->exists)@method('PUT')@endif
        <div class="grid gap-6 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
                <div class="card"><div class="card-body space-y-4">
                    <x-admin.input name="title" label="Başlık" :value="$page->title" required maxlength="255" x-on:input="update($event.target.value)" />
                    <x-admin.slug :value="$page->slug" :prefix="url('/').'/'" />
                    <x-admin.textarea name="excerpt" label="Kısa Açıklama" :value="$page->excerpt" maxlength="500" rows="2" help="Sayfa başlığının altında ve listelerde görünür." />
                    <x-admin.editor name="content" label="İçerik" :value="$page->content" />
                </div></div>
                <x-admin.seo-fields :model="$page" />
            </div>
            <div class="space-y-6">
                <div class="card">
                    <div class="card-header"><h2 class="font-semibold text-slate-900">Yayın</h2></div>
                    <div class="card-body space-y-4">
                        <x-admin.checkbox name="is_published" label="Yayınla" :checked="$page->is_published" help="Kapalıysa sayfa taslak olarak kalır." />
                        <x-admin.input name="published_at" label="Yayın Tarihi" type="datetime-local" :value="$page->published_at?->format('Y-m-d\TH:i')" />
                        <x-admin.select name="template" label="Şablon" :options="config('cms.page_templates')" :value="$page->template" />
                        <x-admin.input name="sort_order" label="Sıra" type="number" min="0" :value="$page->sort_order" />
                    </div>
                </div>
                <div class="card">
                    <div class="card-header"><h2 class="font-semibold text-slate-900">Menü</h2></div>
                    <div class="card-body space-y-3">
                        <x-admin.checkbox name="show_in_menu" label="Üst menüde göster" :checked="$page->show_in_menu" />
                        <x-admin.checkbox name="show_in_footer" label="Alt bilgide (footer) göster" :checked="$page->show_in_footer" />
                    </div>
                </div>
                <div class="card"><div class="card-body"><x-admin.image name="image" label="Kapak Görseli" :value="$page->image" /></div></div>
                <x-admin.form-actions :back="route('admin.pages.index')">
                    @if($page->exists && $page->is_published)<a href="{{ $page->url() }}" target="_blank" class="btn-secondary"><x-icon name="external" class="h-4 w-4" /> Görüntüle</a>@endif
                </x-admin.form-actions>
            </div>
        </div>
    </form>
@endsection
