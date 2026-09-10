@props(['placeholder' => 'Ara...'])
<form method="get" class="flex items-center gap-2">
    {{ $slot }}
    <div class="relative">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="{{ $placeholder }}" class="form-input !w-56 !pl-9">
        <x-icon name="search" class="absolute top-2.5 left-3 h-4 w-4 text-slate-400" />
    </div>
    <button type="submit" class="btn-secondary">Filtrele</button>
    @if(request()->hasAny(['q', 'status', 'category', 'type', 'role', 'doctor', 'kind']))<a href="{{ url()->current() }}" class="btn-ghost">Temizle</a>@endif
</form>
