@extends('layouts.admin')
@section('title', $service->exists ? 'Hizmet Düzenle' : 'Yeni Hizmet')
@section('content')
    <x-admin.page-header :title="$service->exists ? 'Hizmet Düzenle' : 'Yeni Hizmet'" :subtitle="$service->exists ? $service->title : null" />

    <form method="post" action="{{ $service->exists ? route('admin.services.update', $service) : route('admin.services.store') }}" x-data="slugField(@js(old('slug', $service->slug ?? '')), {{ $service->exists ? 'true' : 'false' }})">
        @csrf
        @if($service->exists)@method('PUT')@endif
        <div class="grid gap-6 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
                <div class="card"><div class="card-body space-y-4">
                    <x-admin.input name="title" label="Hizmet Adı" :value="$service->title" required maxlength="255" x-on:input="update($event.target.value)" />
                    <x-admin.slug :value="$service->slug" :prefix="url('/hizmetler').'/'" />
                    <x-admin.textarea name="excerpt" label="Kısa Açıklama" :value="$service->excerpt" maxlength="500" rows="2" help="Kartlarda ve listelerde görünür." />
                    <x-admin.editor name="content" label="Detaylı İçerik" :value="$service->content" />
                </div></div>
                <x-admin.seo-fields :model="$service" />
            </div>
            <div class="space-y-6">
                <div class="card">
                    <div class="card-header"><h2 class="font-semibold text-slate-900">Görünüm</h2></div>
                    <div class="card-body space-y-4">
                        <x-admin.checkbox name="is_active" label="Aktif" :checked="$service->is_active" />
                        <x-admin.checkbox name="is_featured" label="Öne çıkan" :checked="$service->is_featured" help="Ana sayfada öncelikli gösterilir." />
                        <x-admin.input name="sort_order" label="Sıra" type="number" min="0" :value="$service->sort_order" />
                        <x-admin.select name="icon" label="İkon" :options="['briefcase' => 'Çanta', 'cog' => 'Dişli', 'shield' => 'Kalkan', 'chart' => 'Grafik', 'wrench' => 'Anahtar', 'star' => 'Yıldız', 'home' => 'Ev', 'users' => 'Kullanıcılar', 'truck' => 'Kamyon', 'factory' => 'Fabrika', 'tooth' => 'Diş', 'certificate' => 'Sertifika', 'currency' => 'Para', 'check-circle' => 'Onay', 'building' => 'Bina', 'key' => 'Anahtar (kilit)', 'calendar' => 'Takvim', 'eye' => 'Göz', 'heart' => 'Kalp', 'scale' => 'Terazi', 'code' => 'Kod', 'cpu' => 'Çip', 'rocket' => 'Roket', 'academic-cap' => 'Mezuniyet', 'layers' => 'Katmanlar', 'globe' => 'Dünya', 'document' => 'Belge', 'phone' => 'Telefon']" :value="$service->icon ?: 'briefcase'" help="Görsel yoksa ikon gösterilir." />
                    </div>
                </div>
                <div class="card"><div class="card-body"><x-admin.image name="image" label="Görsel" :value="$service->image" /></div></div>
                <x-admin.form-actions :back="route('admin.services.index')" />
            </div>
        </div>
    </form>
@endsection
