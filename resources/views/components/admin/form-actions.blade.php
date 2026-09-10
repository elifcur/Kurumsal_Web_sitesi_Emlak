@props(['back', 'label' => 'Kaydet'])
<div class="flex items-center justify-between gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
    <a href="{{ $back }}" class="btn-ghost"><x-icon name="arrow-left" class="h-4 w-4" /> Geri</a>
    <div class="flex items-center gap-2">
        {{ $slot }}
        <button type="submit" class="btn-primary"><x-icon name="check" class="h-4 w-4" /> {{ $label }}</button>
    </div>
</div>
