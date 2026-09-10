@extends('layouts.admin')
@section('title', 'Sık Sorulan Sorular')
@section('content')
    <x-admin.page-header title="Sık Sorulan Sorular" subtitle="Sürükleyip bırakarak sıralayabilirsiniz.">
        <x-slot:actions><a href="{{ route('admin.faqs.create') }}" class="btn-primary"><x-icon name="plus" class="h-4 w-4" /> Yeni Soru</a></x-slot:actions>
    </x-admin.page-header>

    <div class="card" x-data="sortableList(@js(route('admin.faqs.reorder')))">
        <div class="card-header">
            <x-admin.search placeholder="Soru ara..." />
            <span x-show="saved" x-cloak x-transition class="badge-green">Sıralama kaydedildi</span>
        </div>
        @if($faqs->isEmpty())
            <x-admin.empty title="Soru bulunamadı" icon="info" />
        @else
            <div class="overflow-x-auto"><table class="table">
                <thead><tr><th class="w-8"></th><th>Soru</th><th>Grup</th><th>Durum</th><th class="text-right">İşlem</th></tr></thead>
                <tbody x-ref="list">
                @foreach($faqs as $faq)
                    <tr data-id="{{ $faq->id }}" draggable="true" @dragstart="dragStart($event)" @dragover.prevent="dragOver($event)" @drop.prevent="drop($event)" @dragend="dragEnd()" class="cursor-move">
                        <td class="text-slate-300"><x-icon name="bars-arrow" class="h-4 w-4" /></td>
                        <td><a href="{{ route('admin.faqs.edit', $faq) }}" class="font-medium text-slate-900 hover:text-primary">{{ $faq->question }}</a></td>
                        <td class="text-slate-500">{{ $faq->group ?: '—' }}</td>
                        <td><x-admin.status-badge :active="$faq->is_active" /></td>
                        <td class="text-right whitespace-nowrap">
                            <a href="{{ route('admin.faqs.edit', $faq) }}" class="btn-ghost btn-sm"><x-icon name="pencil" class="h-4 w-4" /></a>
                            <x-admin.delete-button :action="route('admin.faqs.destroy', $faq)" />
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table></div>
            <div class="px-5 py-4">{{ $faqs->links() }}</div>
        @endif
    </div>
@endsection
