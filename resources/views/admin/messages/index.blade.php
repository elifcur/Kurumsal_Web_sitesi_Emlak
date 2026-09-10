@extends('layouts.admin')
@section('title', 'Mesajlar')
@section('content')
    <x-admin.page-header title="Gelen Mesajlar" subtitle="İletişim formu ve modül taleplerinden gelen mesajlar." />

    <div class="card" x-data="{ checked: [] }">
        <div class="card-header">
            <x-admin.search placeholder="Ad, e-posta veya konu...">
                <select name="status" class="form-input !w-36"><option value="">Tümü</option><option value="unread" @selected(request('status') === 'unread')>Okunmamış</option><option value="read" @selected(request('status') === 'read')>Okunmuş</option></select>
                <select name="type" class="form-input !w-40"><option value="">Tüm türler</option>@foreach($types as $key => $label)<option value="{{ $key }}" @selected(request('type') === $key)>{{ $label }}</option>@endforeach</select>
            </x-admin.search>
        </div>
        @if($messages->isEmpty())
            <x-admin.empty title="Mesaj bulunamadı" icon="inbox" />
        @else
            <form method="post" action="{{ route('admin.messages.bulk') }}" onsubmit="return this.action.value !== 'delete' || confirmDelete('Seçili mesajlar silinecek. Devam edilsin mi?')">
                @csrf
                <div class="flex items-center gap-2 border-b border-slate-200 bg-slate-50 px-5 py-2 text-sm" x-show="checked.length" x-cloak>
                    <span class="text-slate-600"><span x-text="checked.length"></span> seçili:</span>
                    <button type="submit" name="action" value="read" class="btn-secondary btn-sm">Okundu işaretle</button>
                    <button type="submit" name="action" value="unread" class="btn-secondary btn-sm">Okunmadı işaretle</button>
                    <button type="submit" name="action" value="delete" class="btn-danger btn-sm">Sil</button>
                </div>
                <div class="overflow-x-auto"><table class="table">
                    <thead><tr><th class="w-8"><input type="checkbox" class="form-checkbox" @change="checked = $event.target.checked ? @js($messages->pluck('id')) : []"></th><th>Gönderen</th><th>Konu</th><th>Tür</th><th>Tarih</th><th class="text-right">İşlem</th></tr></thead>
                    <tbody>
                    @foreach($messages as $message)
                        <tr @class(['bg-blue-50/40 font-semibold' => !$message->is_read])>
                            <td><input type="checkbox" name="ids[]" value="{{ $message->id }}" class="form-checkbox" x-model="checked"></td>
                            <td><a href="{{ route('admin.messages.show', $message) }}" class="text-slate-900 hover:text-primary">{{ $message->name }}</a><span class="block text-xs font-normal text-slate-500">{{ $message->email }}</span></td>
                            <td class="max-w-xs truncate">{{ $message->subject ?: \Illuminate\Support\Str::limit($message->message, 60) }}</td>
                            <td><span class="badge-blue">{{ $message->typeLabel() }}</span></td>
                            <td class="whitespace-nowrap text-xs font-normal text-slate-500">{{ $message->created_at->format('d.m.Y H:i') }}</td>
                            <td class="text-right whitespace-nowrap">
                                <a href="{{ route('admin.messages.show', $message) }}" class="btn-ghost btn-sm"><x-icon name="eye" class="h-4 w-4" /></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table></div>
            </form>
            <div class="px-5 py-4">{{ $messages->links() }}</div>
        @endif
    </div>
@endsection
