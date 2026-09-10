@extends('layouts.admin')
@section('title', 'Kategoriler')
@section('content')
    <x-admin.page-header title="Kategoriler" subtitle="Ürün ve blog kategorilerini yönetin.">
        <x-slot:actions><a href="{{ route('admin.categories.create', ['type' => $type]) }}" class="btn-primary"><x-icon name="plus" class="h-4 w-4" /> Yeni Kategori</a></x-slot:actions>
    </x-admin.page-header>

    <div class="mb-4 flex gap-2">
        @foreach($types as $key => $label)
            <a href="{{ route('admin.categories.index', ['type' => $key]) }}" @class(['rounded-lg px-4 py-2 text-sm font-medium', 'bg-primary text-white' => $type === $key, 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50' => $type !== $key])>{{ $label }}</a>
        @endforeach
    </div>

    <div class="card">
        <div class="card-header"><x-admin.search placeholder="Kategori ara..."><input type="hidden" name="type" value="{{ $type }}"></x-admin.search></div>
        @if($categories->isEmpty())
            <x-admin.empty title="Kategori bulunamadı" icon="tag" />
        @else
            <div class="overflow-x-auto"><table class="table">
                <thead><tr><th>Ad</th><th>Slug</th><th>Üst Kategori</th><th>İçerik</th><th>Sıra</th><th>Durum</th><th class="text-right">İşlem</th></tr></thead>
                <tbody>
                @foreach($categories as $category)
                    <tr>
                        <td><a href="{{ route('admin.categories.edit', $category) }}" class="font-medium text-slate-900 hover:text-primary">{{ $category->name }}</a></td>
                        <td class="text-slate-500">{{ $category->slug }}</td>
                        <td>{{ $category->parent?->name ?? '—' }}</td>
                        <td>{{ $type === 'product' ? $category->products_count.' ürün' : $category->posts_count.' yazı' }}</td>
                        <td>{{ $category->sort_order }}</td>
                        <td><x-admin.status-badge :active="$category->is_active" /></td>
                        <td class="text-right whitespace-nowrap">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="btn-ghost btn-sm"><x-icon name="pencil" class="h-4 w-4" /></a>
                            <x-admin.delete-button :action="route('admin.categories.destroy', $category)" message="Kategori silinecek; bağlı içerikler kategorisiz kalacak. Devam edilsin mi?" />
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table></div>
            <div class="px-5 py-4">{{ $categories->links() }}</div>
        @endif
    </div>
@endsection
