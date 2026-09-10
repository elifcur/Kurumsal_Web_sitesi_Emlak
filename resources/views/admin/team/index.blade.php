@extends('layouts.admin')
@section('title', 'Ekip')
@section('content')
    <x-admin.page-header title="Ekip" subtitle="Doktor, avukat, danışman, mühendis... Sürükleyip bırakarak sıralayabilirsiniz.">
        <x-slot:actions><a href="{{ route('admin.team.create') }}" class="btn-primary"><x-icon name="plus" class="h-4 w-4" /> Yeni Üye</a></x-slot:actions>
    </x-admin.page-header>

    <div class="card" x-data="sortableList(@js(route('admin.team.reorder')))">
        <div class="card-header">
            <x-admin.search placeholder="İsim ara...">
                <select name="status" class="form-input !w-40"><option value="">Tüm durumlar</option><option value="active" @selected(request('status') === 'active')>Aktif</option><option value="inactive" @selected(request('status') === 'inactive')>Pasif</option></select>
            </x-admin.search>
            <span x-show="saved" x-cloak x-transition class="badge-green">Sıralama kaydedildi</span>
        </div>
        @if($members->isEmpty())
            <x-admin.empty title="Ekip üyesi bulunamadı" icon="users" />
        @else
            <div class="overflow-x-auto"><table class="table">
                <thead><tr><th class="w-8"></th><th>Üye</th><th>Unvan</th><th>Öne Çıkan</th><th>Durum</th><th class="text-right">İşlem</th></tr></thead>
                <tbody x-ref="list">
                @foreach($members as $member)
                    <tr data-id="{{ $member->id }}" draggable="true" @dragstart="dragStart($event)" @dragover.prevent="dragOver($event)" @drop.prevent="drop($event)" @dragend="dragEnd()" class="cursor-move">
                        <td class="text-slate-300"><x-icon name="bars-arrow" class="h-4 w-4" /></td>
                        <td class="flex items-center gap-3">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center overflow-hidden rounded-full bg-primary-light text-primary">@if($member->image)<img src="{{ upload_url($member->image) }}" class="h-9 w-9 object-cover" alt="">@else<x-icon name="user" class="h-4 w-4" />@endif</span>
                            <a href="{{ route('admin.team.edit', $member) }}" class="font-medium text-slate-900 hover:text-primary">{{ $member->name }}</a>
                        </td>
                        <td class="text-slate-500">{{ $member->title }}</td>
                        <td>@if($member->is_featured)<span class="badge-blue">Evet</span>@else<span class="text-slate-400">—</span>@endif</td>
                        <td><x-admin.status-badge :active="$member->is_active" /></td>
                        <td class="text-right whitespace-nowrap">
                            <a href="{{ $member->url() }}" target="_blank" class="btn-ghost btn-sm"><x-icon name="external" class="h-4 w-4" /></a>
                            <a href="{{ route('admin.team.edit', $member) }}" class="btn-ghost btn-sm"><x-icon name="pencil" class="h-4 w-4" /></a>
                            <x-admin.delete-button :action="route('admin.team.destroy', $member)" />
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table></div>
            <div class="px-5 py-4">{{ $members->links() }}</div>
        @endif
    </div>
@endsection
