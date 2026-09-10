@extends('layouts.admin')
@section('title', 'Mesaj Detayı')
@section('content')
    <x-admin.page-header :title="$message->subject ?: 'Mesaj #'.$message->id" :subtitle="$message->typeLabel().' · '.$message->created_at->format('d.m.Y H:i')">
        <x-slot:actions>
            <a href="{{ route('admin.messages.index') }}" class="btn-ghost"><x-icon name="arrow-left" class="h-4 w-4" /> Listeye Dön</a>
            <form method="post" action="{{ route('admin.messages.toggle-read', $message) }}">@csrf @method('PATCH')<button type="submit" class="btn-secondary"><x-icon name="envelope-open" class="h-4 w-4" /> {{ $message->is_read ? 'Okunmadı İşaretle' : 'Okundu İşaretle' }}</button></form>
            <x-admin.delete-button :action="route('admin.messages.destroy', $message)" label="Sil" class="btn-danger" />
        </x-slot:actions>
    </x-admin.page-header>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="card lg:col-span-2">
            <div class="card-body">
                <div class="prose-sm whitespace-pre-line text-slate-800">{{ $message->message }}</div>
                @if($message->meta)
                    <dl class="mt-6 divide-y divide-slate-100 rounded-lg border border-slate-200 text-sm">
                        @foreach($message->meta as $key => $value)
                            <div class="flex gap-4 px-4 py-2"><dt class="w-40 shrink-0 text-slate-500">{{ \Illuminate\Support\Str::headline($key) }}</dt><dd class="text-slate-900">{{ is_array($value) ? implode(', ', $value) : $value }}</dd></div>
                        @endforeach
                    </dl>
                @endif
            </div>
        </div>
        <div class="card">
            <div class="card-header"><h2 class="font-semibold text-slate-900">Gönderen</h2></div>
            <dl class="divide-y divide-slate-100 text-sm">
                <div class="px-5 py-3"><dt class="text-xs text-slate-500">Ad</dt><dd class="font-medium text-slate-900">{{ $message->name }}</dd></div>
                <div class="px-5 py-3"><dt class="text-xs text-slate-500">E-posta</dt><dd><a href="mailto:{{ $message->email }}?subject={{ rawurlencode('Re: '.($message->subject ?: 'Mesajınız')) }}" class="font-medium text-primary hover:underline">{{ $message->email }}</a></dd></div>
                @if($message->phone)<div class="px-5 py-3"><dt class="text-xs text-slate-500">Telefon</dt><dd><a href="tel:{{ $message->phone }}" class="font-medium text-primary hover:underline">{{ $message->phone }}</a></dd></div>@endif
                <div class="px-5 py-3"><dt class="text-xs text-slate-500">IP</dt><dd class="text-slate-700">{{ $message->ip ?? '—' }}</dd></div>
                <div class="px-5 py-3"><dt class="text-xs text-slate-500">Tarayıcı</dt><dd class="break-all text-xs text-slate-600">{{ $message->user_agent ?? '—' }}</dd></div>
                <div class="px-5 py-3"><dt class="text-xs text-slate-500">Okunma</dt><dd class="text-slate-700">{{ $message->read_at?->format('d.m.Y H:i') ?? '—' }}</dd></div>
            </dl>
            <div class="border-t border-slate-100 p-5">
                <a href="mailto:{{ $message->email }}?subject={{ rawurlencode('Re: '.($message->subject ?: 'Mesajınız')) }}" class="btn-primary w-full"><x-icon name="mail" class="h-4 w-4" /> E-posta ile Yanıtla</a>
            </div>
        </div>
    </div>
@endsection
