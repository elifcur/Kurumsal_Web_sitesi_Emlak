@props(['title' => 'Kayıt bulunamadı', 'text' => null, 'icon' => 'inbox'])
<div class="px-6 py-16 text-center">
    <x-icon :name="$icon" class="mx-auto h-10 w-10 text-slate-300" />
    <p class="mt-3 font-semibold text-slate-700">{{ $title }}</p>
    @if($text)<p class="mt-1 text-sm text-slate-500">{{ $text }}</p>@endif
    @if(isset($action))<div class="mt-5">{{ $action }}</div>@endif
</div>
