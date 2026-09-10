@extends('layouts.admin')
@section('title', 'Sayfalar')
@section('content')
    <x-admin.page-header title="Sayfalar" subtitle="Kurumsal sayfaları yönetin.">
        <x-slot:actions><a href="{{ route('admin.pages.create') }}" class="btn-primary"><x-icon name="plus" class="h-4 w-4" /> Yeni Sayfa</a></x-slot:actions>
    </x-admin.page-header>

    <div class="card">
        <div class="card-header">
            <x-admin.search placeholder="Sayfa ara...">
                <select name="status" class="form-input !w-40"><option value="">Tüm durumlar</option><option value="published" @selected(request('status') === 'published')>Yayında</option><option value="draft" @selected(request('status') === 'draft')>Taslak</option></select>
            </x-admin.search>
        </div>
        @if($pages->isEmpty())
            <x-admin.empty title="Sayfa bulunamadı" icon="document" />
        @else
            <div class="overflow-x-auto"><table class="table">
                <thead><tr><th>Başlık</th><th>URL</th><th>Şablon</th><th>Menü</th><th>Durum</th><th>Güncelleme</th><th class="text-right">İşlem</th></tr></thead>
                <tbody>
                @foreach($pages as $page)
                    <tr>
                        <td><a href="{{ route('admin.pages.edit', $page) }}" class="font-medium text-slate-900 hover:text-primary">{{ $page->title }}</a>@if($page->is_system)<span class="badge-gray ml-2">sistem</span>@endif</td>
                        <td class="text-slate-500">/{{ $page->slug }}</td>
                        <td>{{ config('cms.page_templates.'.$page->template, $page->template) }}</td>
                        <td class="text-xs text-slate-500">{{ $page->show_in_menu ? 'Üst' : '' }}{{ $page->show_in_menu && $page->show_in_footer ? ' + ' : '' }}{{ $page->show_in_footer ? 'Alt' : '' }}{{ !$page->show_in_menu && !$page->show_in_footer ? '—' : '' }}</td>
                        <td><x-admin.status-badge :active="$page->is_published" on="Yayında" off="Taslak" /></td>
                        <td class="whitespace-nowrap text-xs text-slate-500">{{ $page->updated_at->format('d.m.Y H:i') }}</td>
                        <td class="text-right whitespace-nowrap">
                            @if($page->is_published)<a href="{{ $page->url() }}" target="_blank" class="btn-ghost btn-sm" title="Görüntüle"><x-icon name="external" class="h-4 w-4" /></a>@endif
                            <a href="{{ route('admin.pages.edit', $page) }}" class="btn-ghost btn-sm" title="Düzenle"><x-icon name="pencil" class="h-4 w-4" /></a>
                            @unless($page->is_system)<x-admin.delete-button :action="route('admin.pages.destroy', $page)" />@endunless
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table></div>
            <div class="px-5 py-4">{{ $pages->links() }}</div>
        @endif
    </div>
@endsection
