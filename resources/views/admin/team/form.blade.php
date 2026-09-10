@extends('layouts.admin')
@section('title', $member->exists ? 'Ekip Üyesi Düzenle' : 'Yeni Ekip Üyesi')
@section('content')
    <x-admin.page-header :title="$member->exists ? 'Ekip Üyesi Düzenle' : 'Yeni Ekip Üyesi'" :subtitle="$member->exists ? $member->name : null" />

    <form method="post" action="{{ $member->exists ? route('admin.team.update', $member) : route('admin.team.store') }}" x-data="slugField(@js(old('slug', $member->slug ?? '')), {{ $member->exists ? 'true' : 'false' }})">
        @csrf
        @if($member->exists)@method('PUT')@endif
        <div class="grid gap-6 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
                <div class="card"><div class="card-body space-y-4">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <x-admin.input name="name" label="Ad Soyad" :value="$member->name" required maxlength="150" x-on:input="update($event.target.value)" />
                        <x-admin.input name="title" label="Unvan / Görev" :value="$member->title" maxlength="150" help="Örn. Kardiyolog, Kıdemli Avukat, Satış Danışmanı" />
                    </div>
                    <x-admin.slug :value="$member->slug" :prefix="url('/ekibimiz').'/'" />
                    <x-admin.textarea name="excerpt" label="Kısa Tanıtım" :value="$member->excerpt" maxlength="500" rows="2" help="Kartlarda görünür." />
                    <x-admin.editor name="bio" label="Biyografi" :value="$member->bio" />
                </div></div>
                <div class="card">
                    <div class="card-header"><h2 class="font-semibold text-slate-900">Ek Bilgiler</h2></div>
                    <div class="card-body space-y-4">
                        <x-admin.repeater name="attributes" label="Özellikler" :value="$member->attributes_list()" labelPlaceholder="Uzmanlık, Deneyim, Diller..." valuePlaceholder="Değer" help="Şablona göre kartlarda ve profil sayfasında gösterilir." />
                    </div>
                </div>
            </div>
            <div class="space-y-6">
                <div class="card">
                    <div class="card-header"><h2 class="font-semibold text-slate-900">Görünüm</h2></div>
                    <div class="card-body space-y-4">
                        <x-admin.checkbox name="is_active" label="Aktif" :checked="$member->is_active" />
                        <x-admin.checkbox name="is_featured" label="Öne çıkan" :checked="$member->is_featured" help="Ana sayfada gösterilir." />
                        <x-admin.input name="sort_order" label="Sıra" type="number" min="0" :value="$member->sort_order" />
                    </div>
                </div>
                <div class="card"><div class="card-body"><x-admin.image name="image" label="Fotoğraf" :value="$member->image" help="Kare (1:1) görsel önerilir." /></div></div>
                <div class="card">
                    <div class="card-header"><h2 class="font-semibold text-slate-900">İletişim</h2></div>
                    <div class="card-body space-y-4">
                        <x-admin.input name="email" label="E-posta" type="email" :value="$member->email" maxlength="190" />
                        <x-admin.input name="phone" label="Telefon" :value="$member->phone" maxlength="40" />
                        <x-admin.input name="socials[linkedin]" label="LinkedIn" type="url" :value="$member->social('linkedin')" />
                        <x-admin.input name="socials[instagram]" label="Instagram" type="url" :value="$member->social('instagram')" />
                        <x-admin.input name="socials[twitter]" label="X (Twitter)" type="url" :value="$member->social('twitter')" />
                    </div>
                </div>
                <x-admin.form-actions :back="route('admin.team.index')" />
            </div>
        </div>
    </form>
@endsection
