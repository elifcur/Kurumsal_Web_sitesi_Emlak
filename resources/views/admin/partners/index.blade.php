@extends('layouts.admin')
@section('title', 'Partnerler & Referans Logoları')
@section('content')
    <x-admin.page-header title="Partnerler & Referans Logoları" subtitle="Ana sayfadaki logo şeridinde gösterilir.">
        <x-slot:actions><a href="{{ route('admin.partners.create') }}" class="btn-primary"><x-icon name="plus" class="h-4 w-4" /> Yeni Partner</a></x-slot:actions>
    </x-admin.page-header>

    <div class="card">
        <div class="card-header"><x-admin.search placeholder="İsim ara..." /></div>
        @if($partners->isEmpty())
            <x-admin.empty title="Partner bulunamadı" icon="globe" />
        @else
            <div class="overflow-x-auto"><table class="table">
                <thead><tr><th>Logo</th><th>Ad</th><th>Bağlantı</th><th>Sıra</th><th>Durum</th><th class="text-right">İşlem</th></tr></thead>
                <tbody>
                @foreach($partners as $partner)
                    <tr>
                        <td>@if($partner->logo)<img src="{{ upload_url($partner->logo) }}" alt="" class="h-8 w-auto max-w-[120px] object-contain">@else<span class="text-slate-400">—</span>@endif</td>
                        <td><a href="{{ route('admin.partners.edit', $partner) }}" class="font-medium text-slate-900 hover:text-primary">{{ $partner->name }}</a></td>
                        <td class="max-w-xs truncate text-slate-500">{{ $partner->url }}</td>
                        <td class="text-slate-500">{{ $partner->sort_order }}</td>
                        <td><x-admin.status-badge :active="$partner->is_active" /></td>
                        <td class="text-right whitespace-nowrap">
                            <a href="{{ route('admin.partners.edit', $partner) }}" class="btn-ghost btn-sm"><x-icon name="pencil" class="h-4 w-4" /></a>
                            <x-admin.delete-button :action="route('admin.partners.destroy', $partner)" />
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table></div>
            <div class="px-5 py-4">{{ $partners->links() }}</div>
        @endif
    </div>
@endsection
