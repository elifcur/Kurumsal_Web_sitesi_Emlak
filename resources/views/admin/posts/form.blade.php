@extends('layouts.admin')
@section('title', $post->exists ? 'Yazı Düzenle' : 'Yeni Yazı')
@section('content')
    <x-admin.page-header :title="$post->exists ? 'Yazı Düzenle' : 'Yeni Yazı'" :subtitle="$post->exists ? $post->title : null" />

    <form method="post" action="{{ $post->exists ? route('admin.posts.update', $post) : route('admin.posts.store') }}" x-data="slugField(@js(old('slug', $post->slug ?? '')), {{ $post->exists ? 'true' : 'false' }})">
        @csrf
        @if($post->exists)@method('PUT')@endif
        <div class="grid gap-6 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
                <div class="card"><div class="card-body space-y-4">
                    <x-admin.input name="title" label="Başlık" :value="$post->title" required maxlength="255" x-on:input="update($event.target.value)" />
                    <x-admin.slug :value="$post->slug" :prefix="url('/blog').'/'" />
                    <x-admin.textarea name="excerpt" label="Özet" :value="$post->excerpt" maxlength="500" rows="2" help="Listelerde ve yazının başında görünür." />
                    <x-admin.editor name="content" label="İçerik" :value="$post->content" />
                </div></div>
                <x-admin.seo-fields :model="$post" />
            </div>
            <div class="space-y-6">
                <div class="card">
                    <div class="card-header"><h2 class="font-semibold text-slate-900">Yayın</h2></div>
                    <div class="card-body space-y-4">
                        <x-admin.select name="status" label="Durum" :options="\App\Enums\PostStatus::options()" :value="$post->status?->value" required />
                        <x-admin.input name="published_at" label="Yayın Tarihi" type="datetime-local" :value="$post->published_at?->format('Y-m-d\TH:i')" help="İleri bir tarih seçerek zamanlayabilirsiniz." />
                        <x-admin.select name="category_id" label="Kategori" :options="$categories" :value="$post->category_id" placeholder="— Seçin —" />
                        <x-admin.input name="tags" label="Etiketler" :value="$tags" help="Virgülle ayırın: teknoloji, kalite" />
                    </div>
                </div>
                <div class="card"><div class="card-body"><x-admin.image name="image" label="Kapak Görseli" :value="$post->image" /></div></div>
                <x-admin.form-actions :back="route('admin.posts.index')">
                    @if($post->exists && $post->isPublished())<a href="{{ $post->url() }}" target="_blank" class="btn-secondary"><x-icon name="external" class="h-4 w-4" /> Görüntüle</a>@endif
                </x-admin.form-actions>
            </div>
        </div>
    </form>
@endsection
