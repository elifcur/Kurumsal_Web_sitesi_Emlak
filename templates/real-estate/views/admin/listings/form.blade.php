@extends('layouts.admin')
@section('title', $listing->exists ? 'İlan Düzenle' : 'Yeni İlan')
@section('content')
    <x-admin.page-header :title="$listing->exists ? 'İlan Düzenle' : 'Yeni İlan'" :subtitle="$listing->exists ? $listing->title : null" />
    <form method="post" action="{{ $listing->exists ? route('admin.listings.update', $listing) : route('admin.listings.store') }}" x-data="slugField(@js(old('slug', $listing->slug ?? '')), {{ $listing->exists ? 'true' : 'false' }})">
        @csrf
        @if($listing->exists)@method('PUT')@endif
        <div class="grid gap-6 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
                <div class="card"><div class="card-body space-y-4">
                    <x-admin.input name="title" label="İlan Başlığı" :value="$listing->title" required x-on:input="update($event.target.value)" />
                    <x-admin.slug :value="$listing->slug" :prefix="url('/ilanlar').'/'" />
                    <div class="grid gap-4 sm:grid-cols-3">
                        <x-admin.select name="listing_type" label="İlan Türü" :options="\Templates\RealEstate\Models\Listing::TYPES" :value="$listing->listing_type" required />
                        <x-admin.select name="property_type" label="Gayrimenkul Tipi" :options="\Templates\RealEstate\Models\Listing::PROPERTY_TYPES" :value="$listing->property_type" required />
                        <div class="grid grid-cols-3 gap-2">
                            <x-admin.input name="price" label="Fiyat" type="number" step="0.01" min="0" :value="$listing->price" wrapperClass="col-span-2" />
                            <x-admin.select name="currency" label="Birim" :options="['TRY' => '₺', 'USD' => '$', 'EUR' => '€']" :value="$listing->currency" />
                        </div>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <x-admin.input name="city" label="Şehir" :value="$listing->city" />
                        <x-admin.input name="district" label="İlçe" :value="$listing->district" />
                    </div>
                    <x-admin.input name="address" label="Adres" :value="$listing->address" />
                    <x-admin.textarea name="description" label="Açıklama" :value="$listing->description" rows="8" />
                    <x-admin.input name="features" label="Özellikler" :value="is_array($listing->features) ? implode(', ', $listing->features) : $listing->features" help="Virgülle ayırın: Asansör, Otopark, Güvenlik" />
                    <div class="grid gap-4 sm:grid-cols-2">
                        <x-admin.input name="video_url" label="Video (YouTube / Vimeo)" :value="$listing->video_url" placeholder="https://youtu.be/..." />
                        <x-admin.input name="map_embed" label="Google Harita Embed URL" :value="$listing->map_embed" help="https://www.google.com/maps/embed?... ile başlamalı" />
                    </div>
                </div></div>
                <div class="card">
                    <div class="card-header"><h2 class="font-semibold text-slate-900">Detaylar</h2></div>
                    <div class="card-body grid gap-4 sm:grid-cols-3">
                        <x-admin.input name="rooms" label="Oda Sayısı" :value="$listing->rooms" placeholder="3+1" />
                        <x-admin.input name="bathrooms" label="Banyo" type="number" min="0" :value="$listing->bathrooms" />
                        <x-admin.input name="floor" label="Kat" :value="$listing->floor" />
                        <x-admin.input name="area_gross" label="Brüt m²" type="number" min="0" :value="$listing->area_gross" />
                        <x-admin.input name="area_net" label="Net m²" type="number" min="0" :value="$listing->area_net" />
                        <x-admin.input name="building_age" label="Bina Yaşı" type="number" min="0" :value="$listing->building_age" />
                        <x-admin.input name="heating" label="Isıtma" :value="$listing->heating" />
                        <x-admin.checkbox name="furnished" label="Eşyalı" :checked="$listing->furnished" class="pt-7" />
                    </div>
                </div>
                <div class="card"><div class="card-header"><h2 class="font-semibold text-slate-900">Galeri</h2></div><div class="card-body"><x-admin.gallery name="gallery" label="" :value="$listing->gallery ?? []" /></div></div>
                <x-admin.seo-fields :model="$listing" />
            </div>
            <div class="space-y-6">
                <div class="card"><div class="card-body space-y-4">
                    <x-admin.checkbox name="is_active" label="Aktif (sitede görünür)" :checked="$listing->is_active" />
                    <x-admin.checkbox name="is_featured" label="Öne çıkan" :checked="$listing->is_featured" help="Ana sayfada ve listede önce gösterilir." />
                    <x-admin.input name="sort_order" label="Sıra" type="number" min="0" :value="$listing->sort_order" />
                    <x-admin.select name="agent_id" label="Sorumlu Danışman" :options="$agents" :value="$listing->agent_id" placeholder="Seçiniz" help="Ekip bölümünden eklenen üyeler." />
                </div></div>
                <div class="card"><div class="card-body"><x-admin.image name="image" label="Kapak Görseli" :value="$listing->image" /></div></div>
                <x-admin.form-actions :back="route('admin.listings.index')" />
            </div>
        </div>
    </form>
@endsection
