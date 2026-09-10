@props(['icon' => 'inbox', 'title' => 'Henüz içerik yok', 'text' => null])
<div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-6 py-16 text-center">
    <x-icon :name="$icon" class="mx-auto h-10 w-10 text-slate-300" />
    <p class="mt-3 font-semibold text-slate-700">{{ $title }}</p>
    @if($text)<p class="mt-1 text-sm text-slate-500">{{ $text }}</p>@endif
</div>
