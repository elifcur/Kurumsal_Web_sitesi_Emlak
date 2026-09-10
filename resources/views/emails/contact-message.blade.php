<x-mail::message>
# Yeni {{ $contactMessage->typeLabel() }}

**Ad:** {{ $contactMessage->name }}<br>
**E-posta:** {{ $contactMessage->email }}<br>
@if($contactMessage->phone)
**Telefon:** {{ $contactMessage->phone }}<br>
@endif
@if($contactMessage->subject)
**Konu:** {{ $contactMessage->subject }}<br>
@endif

---

{{ $contactMessage->message }}

@if($contactMessage->meta)
---
@foreach($contactMessage->meta as $key => $value)
**{{ \Illuminate\Support\Str::headline($key) }}:** {{ is_array($value) ? implode(', ', $value) : $value }}<br>
@endforeach
@endif

<x-mail::button :url="route('admin.messages.show', $contactMessage)">
Yönetim panelinde görüntüle
</x-mail::button>

{{ setting('site_name', config('app.name')) }}
</x-mail::message>
