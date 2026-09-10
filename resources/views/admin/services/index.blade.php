@extends('layouts.admin')
@section('title', 'Hizmetler')
@section('content')
    <x-admin.page-header title="Hizmetler" subtitle="Sürükleyip bırakarak sıralayabilirsiniz.">
        <x-slot:actions><a href="{{ route('admin.services.create') }}" class="btn-primary"><x-icon name="plus" class="h-4 w-4" /> Yeni Hizmet</a></x-slot:actions>
    </x-admin.page-header>

    <div class="card" x-data="sortableList(@js(route('admin.services.reorder')))">
        <div class="card-header">
            <x-admin.search placeholder="Hizmet ara...">
                <select name="status" class="form-input !w-40"><option value="">Tüm durumlar</option><option value="active" @selected(request('status') === 'active')>Aktif</option><option value="inactive" @selected(request('status') === 'inactive')>Pasif</option></select>
            </x-admin.search>
            <span x-show="saved" x-cloak x-transition class="badge-green">Sıralama kaydedildi</span>
        </div>
        @if($services->isEmpty())
            <x-admin.empty title="Hizmet bulunamadı" icon="briefcase" />
        @else
            <div class="overflow-x-auto"><table class="table">
                <thead><tr><th class="w-8"></th><th>Hizmet</th><th>URL</th><th>Öne Çıkan</th><th>Durum</th><th class="text-right">İşlem</th></tr></thead>
                <tbody x-ref="list">
                @foreach($services as $service)
                    <tr data-id="{{ $service->id }}" draggable="true" @dragstart="dragStart($event)" @dragover.prevent="dragOver($event)" @drop.prevent="drop($event)" @dragend="dragEnd()" class="cursor-move">
                        <td class="text-slate-300"><x-icon name="bars-arrow" class="h-4 w-4" /></td>
                        <td class="flex items-center gap-3">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-primary-light text-primary">@if($service->image)<img src="{{ upload_url($service->image) }}" class="h-9 w-9 rounded-lg object-cover" alt="">@else<x-icon :name="$service->icon ?: 'briefcase'" class="h-4 w-4" />@endif</span>
                            <a href="{{ route('admin.services.edit', $service) }}" class="font-medium text-slate-900 hover:text-primary">{{ $service->title }}</a>
                        </td>
                        <td class="text-slate-500">/hizmetler/{{ $service->slug }}</td>
                        <td>@if($service->is_featured)<span class="badge-blue">Evet</span>@else<span class="text-slate-400">—</span>@endif</td>
                        <td><x-admin.status-badge :active="$service->is_active" /></td>
                        <td class="text-right whitespace-nowrap">
                            <a href="{{ $service->url() }}" target="_blank" class="btn-ghost btn-sm"><x-icon name="external" class="h-4 w-4" /></a>
                            <a href="{{ route('admin.services.edit', $service) }}" class="btn-ghost btn-sm"><x-icon name="pencil" class="h-4 w-4" /></a>
                            <x-admin.delete-button :action="route('admin.services.destroy', $service)" />
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table></div>
            <div class="px-5 py-4">{{ $services->links() }}</div>
        @endif
    </div>
@endsection
