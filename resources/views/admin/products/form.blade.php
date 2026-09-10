@extends('layouts.admin')
@section('title', $product->exists ? 'Ürün Düzenle' : 'Yeni Ürün')
@section('content')
    <x-admin.page-header :title="$product->exists ? 'Ürün Düzenle' : 'Yeni Ürün'" :subtitle="$product->exists ? $product->title : null" />

    <form method="post" action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}" x-data="slugField(@js(old('slug', $product->slug ?? '')), {{ $product->exists ? 'true' : 'false' }})">
        @csrf
        @if($product->exists)@method('PUT')@endif
        <div class="grid gap-6 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
                <div class="card"><div class="card-body space-y-4">
                    <x-admin.input name="title" label="Ürün Adı" :value="$product->title" required maxlength="255" x-on:input="update($event.target.value)" />
                    <x-admin.slug :value="$product->slug" :prefix="url('/urunler').'/'" />
                    <div class="grid gap-4 sm:grid-cols-2">
                        <x-admin.select name="category_id" label="Kategori" :options="$categories" :value="$product->category_id" placeholder="— Seçin —" />
                        <x-admin.input name="sku" label="Ürün Kodu (SKU)" :value="$product->sku" maxlength="100" />
                    </div>
                    <x-admin.textarea name="excerpt" label="Kısa Açıklama" :value="$product->excerpt" maxlength="500" rows="2" />
                    <x-admin.editor name="content" label="Detaylı Açıklama" :value="$product->content" />
                </div></div>
                <div class="card">
                    <div class="card-header"><h2 class="font-semibold text-slate-900">Teknik Özellikler</h2></div>
                    <div class="card-body"><x-admin.repeater name="specs" :value="$product->specs ?? []" labelPlaceholder="Özellik (örn. Malzeme)" valuePlaceholder="Değer (örn. Çelik)" /></div>
                </div>
                <div class="card">
                    <div class="card-header"><h2 class="font-semibold text-slate-900">Galeri</h2></div>
                    <div class="card-body"><x-admin.gallery name="gallery" label="" :value="$product->gallery ?? []" /></div>
                </div>
                <x-admin.seo-fields :model="$product" />
            </div>
            <div class="space-y-6">
                <div class="card">
                    <div class="card-header"><h2 class="font-semibold text-slate-900">Durum &amp; Fiyat</h2></div>
                    <div class="card-body space-y-4">
                        <x-admin.checkbox name="is_active" label="Aktif" :checked="$product->is_active" />
                        <x-admin.checkbox name="is_featured" label="Öne çıkan" :checked="$product->is_featured" help="Ana sayfada gösterilir." />
                        <x-admin.input name="price" label="Fiyat (₺)" type="number" step="0.01" min="0" :value="$product->price" help="Boş bırakılırsa fiyat gösterilmez." />
                        <x-admin.input name="price_note" label="Fiyat Notu" :value="$product->price_note" maxlength="100" placeholder="örn. KDV dahil / Teklif alın" />
                        <x-admin.input name="sort_order" label="Sıra" type="number" min="0" :value="$product->sort_order" />
                    </div>
                </div>
                <div class="card"><div class="card-body"><x-admin.image name="image" label="Ana Görsel" :value="$product->image" /></div></div>
                <x-admin.form-actions :back="route('admin.products.index')" />
            </div>
        </div>
    </form>
@endsection
