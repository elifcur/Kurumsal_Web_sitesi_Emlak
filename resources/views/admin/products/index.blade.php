@extends('layouts.admin')
@section('title', 'Ürünler')
@section('content')
    <x-admin.page-header title="Ürünler" subtitle="Ürün ve çözümlerinizi yönetin.">
        <x-slot:actions><a href="{{ route('admin.categories.index', ['type' => 'product']) }}" class="btn-secondary"><x-icon name="tag" class="h-4 w-4" /> Kategoriler</a><a href="{{ route('admin.products.create') }}" class="btn-primary"><x-icon name="plus" class="h-4 w-4" /> Yeni Ürün</a></x-slot:actions>
    </x-admin.page-header>

    <div class="card">
        <div class="card-header">
            <x-admin.search placeholder="Ürün adı veya kod...">
                <select name="category" class="form-input !w-44"><option value="">Tüm kategoriler</option>@foreach($categories as $id => $name)<option value="{{ $id }}" @selected(request('category') == $id)>{{ $name }}</option>@endforeach</select>
                <select name="status" class="form-input !w-36"><option value="">Tüm durumlar</option><option value="active" @selected(request('status') === 'active')>Aktif</option><option value="inactive" @selected(request('status') === 'inactive')>Pasif</option></select>
            </x-admin.search>
        </div>
        @if($products->isEmpty())
            <x-admin.empty title="Ürün bulunamadı" icon="box" />
        @else
            <div class="overflow-x-auto"><table class="table">
                <thead><tr><th>Ürün</th><th>Kategori</th><th>Kod</th><th>Fiyat</th><th>Öne Çıkan</th><th>Durum</th><th class="text-right">İşlem</th></tr></thead>
                <tbody>
                @foreach($products as $product)
                    <tr>
                        <td class="flex items-center gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center overflow-hidden rounded-lg bg-slate-100 text-slate-400">@if($product->image)<img src="{{ upload_url($product->image) }}" alt="" class="h-full w-full object-cover">@else<x-icon name="box" class="h-5 w-5" />@endif</span>
                            <a href="{{ route('admin.products.edit', $product) }}" class="font-medium text-slate-900 hover:text-primary">{{ $product->title }}</a>
                        </td>
                        <td>{{ $product->category?->name ?? '—' }}</td>
                        <td class="text-slate-500">{{ $product->sku ?: '—' }}</td>
                        <td class="whitespace-nowrap">{{ $product->formattedPrice() ?? ($product->price_note ?: '—') }}</td>
                        <td>@if($product->is_featured)<span class="badge-blue">Evet</span>@else<span class="text-slate-400">—</span>@endif</td>
                        <td><x-admin.status-badge :active="$product->is_active" /></td>
                        <td class="text-right whitespace-nowrap">
                            <a href="{{ $product->url() }}" target="_blank" class="btn-ghost btn-sm"><x-icon name="external" class="h-4 w-4" /></a>
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn-ghost btn-sm"><x-icon name="pencil" class="h-4 w-4" /></a>
                            <x-admin.delete-button :action="route('admin.products.destroy', $product)" />
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table></div>
            <div class="px-5 py-4">{{ $products->links() }}</div>
        @endif
    </div>
@endsection
