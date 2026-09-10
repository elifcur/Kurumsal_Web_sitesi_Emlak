@extends('layouts.admin')
@section('title', 'İlanlar')
@section('content')
    <x-admin.page-header title="İlanlar" subtitle="Satılık ve kiralık gayrimenkul portföyü.">
        <x-slot:actions><a href="{{ route('admin.listings.create') }}" class="btn-primary"><x-icon name="plus" class="h-4 w-4" /> Yeni İlan</a></x-slot:actions>
    </x-admin.page-header>
    <div class="card">
        <div class="card-header">
            <x-admin.search placeholder="İlan ara...">
                <select name="type" class="form-input !w-36"><option value="">Satılık/Kiralık</option>@foreach(\Templates\RealEstate\Models\Listing::TYPES as $k => $l)<option value="{{ $k }}" @selected(request('type') === $k)>{{ $l }}</option>@endforeach</select>
                <select name="status" class="form-input !w-36"><option value="">Tüm durumlar</option><option value="active" @selected(request('status') === 'active')>Aktif</option><option value="inactive" @selected(request('status') === 'inactive')>Pasif</option></select>
            </x-admin.search>
        </div>
        @if($listings->isEmpty())
            <x-admin.empty title="İlan bulunamadı" icon="home" />
        @else
            <div class="overflow-x-auto"><table class="table">
                <thead><tr><th>İlan</th><th>Tür</th><th>Konum</th><th>Fiyat</th><th>Danışman</th><th>Durum</th><th class="text-right">İşlem</th></tr></thead>
                <tbody>
                @foreach($listings as $listing)
                    <tr>
                        <td class="flex items-center gap-3">
                            <span class="h-10 w-14 shrink-0 overflow-hidden rounded-lg bg-slate-100">@if($listing->image)<img src="{{ upload_url($listing->image) }}" class="h-full w-full object-cover" alt="">@endif</span>
                            <span><a href="{{ route('admin.listings.edit', $listing) }}" class="font-medium text-slate-900 hover:text-primary">{{ $listing->title }}</a>@if($listing->is_featured)<span class="badge-blue ml-2">Öne çıkan</span>@endif<span class="block text-xs text-slate-500">{{ $listing->propertyTypeLabel() }} · {{ $listing->rooms }} · {{ $listing->area_gross }} m²</span></span>
                        </td>
                        <td><span class="{{ $listing->listing_type === 'sale' ? 'badge-green' : 'badge-yellow' }}">{{ $listing->typeLabel() }}</span></td>
                        <td class="text-slate-600">{{ $listing->location() }}</td>
                        <td class="font-medium whitespace-nowrap text-slate-900">{{ $listing->formattedPrice() ?? '—' }}</td>
                        <td class="text-slate-600">{{ $listing->agent?->name ?? '—' }}</td>
                        <td><x-admin.status-badge :active="$listing->is_active" /></td>
                        <td class="text-right whitespace-nowrap">
                            <a href="{{ $listing->url() }}" target="_blank" class="btn-ghost btn-sm"><x-icon name="external" class="h-4 w-4" /></a>
                            <a href="{{ route('admin.listings.edit', $listing) }}" class="btn-ghost btn-sm"><x-icon name="pencil" class="h-4 w-4" /></a>
                            <x-admin.delete-button :action="route('admin.listings.destroy', $listing)" />
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table></div>
            <div class="px-5 py-4">{{ $listings->links() }}</div>
        @endif
    </div>
@endsection
