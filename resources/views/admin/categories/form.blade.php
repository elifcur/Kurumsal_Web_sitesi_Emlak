@extends('layouts.admin')
@section('title', $category->exists ? 'Kategori Düzenle' : 'Yeni Kategori')
@section('content')
    <x-admin.page-header :title="$category->exists ? 'Kategori Düzenle' : 'Yeni Kategori'" />
    <form method="post" action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}" class="max-w-3xl" x-data="slugField(@js(old('slug', $category->slug ?? '')), {{ $category->exists ? 'true' : 'false' }})">
        @csrf
        @if($category->exists)@method('PUT')@endif
        <div class="card"><div class="card-body space-y-4">
            <x-admin.select name="type" label="Tür" :options="$types" :value="$category->type" :disabled="$category->exists" required />
            @if($category->exists)<input type="hidden" name="type" value="{{ $category->type }}">@endif
            <x-admin.input name="name" label="Kategori Adı" :value="$category->name" required maxlength="255" x-on:input="update($event.target.value)" />
            <x-admin.slug :value="$category->slug" />
            <x-admin.select name="parent_id" label="Üst Kategori" :options="$parents" :value="$category->parent_id" placeholder="— Yok —" />
            <x-admin.textarea name="description" label="Açıklama" :value="$category->description" maxlength="500" rows="2" />
            <x-admin.image name="image" label="Görsel" :value="$category->image" />
            <div class="grid gap-4 sm:grid-cols-2">
                <x-admin.input name="sort_order" label="Sıra" type="number" min="0" :value="$category->sort_order" />
                <x-admin.checkbox name="is_active" label="Aktif" :checked="$category->is_active" class="pt-7" />
            </div>
        </div></div>
        <div class="mt-6"><x-admin.form-actions :back="route('admin.categories.index', ['type' => $category->type])" /></div>
    </form>
@endsection
