@extends('layouts.admin')
@section('title', $partner->exists ? 'Partner Düzenle' : 'Yeni Partner')
@section('content')
    <x-admin.page-header :title="$partner->exists ? 'Partner Düzenle' : 'Yeni Partner'" />

    <form method="post" action="{{ $partner->exists ? route('admin.partners.update', $partner) : route('admin.partners.store') }}">
        @csrf
        @if($partner->exists)@method('PUT')@endif
        <div class="grid gap-6 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
                <div class="card"><div class="card-body space-y-4">
                    <x-admin.input name="name" label="Ad" :value="$partner->name" required maxlength="150" />
                    <x-admin.input name="url" label="Web Sitesi" type="url" :value="$partner->url" maxlength="255" help="Logoya tıklandığında açılır (isteğe bağlı)." />
                    <x-admin.image name="logo" label="Logo" :value="$partner->logo" help="Şeffaf PNG veya SVG önerilir." />
                </div></div>
            </div>
            <div class="space-y-6">
                <div class="card"><div class="card-body space-y-4">
                    <x-admin.checkbox name="is_active" label="Aktif" :checked="$partner->is_active" />
                    <x-admin.input name="sort_order" label="Sıra" type="number" min="0" :value="$partner->sort_order" />
                </div></div>
                <x-admin.form-actions :back="route('admin.partners.index')" />
            </div>
        </div>
    </form>
@endsection
