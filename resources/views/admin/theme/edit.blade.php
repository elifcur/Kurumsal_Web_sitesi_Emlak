@extends('layouts.admin')
@section('title', 'Tema')
@section('content')
    <x-admin.page-header title="Tema & Görünüm" subtitle="Sitenin genel tasarımını, yazı tipini ve marka rengini seçin. İçerikleriniz temadan bağımsızdır; istediğiniz zaman değiştirebilirsiniz." />

    <form method="post" action="{{ route('admin.theme.update') }}" x-data="{ theme: @js(old('theme', $current)), useThemeColor: {{ old('use_theme_color', '0') ? 'true' : 'false' }}, themeColors: @js(collect($themes)->map(fn ($t) => $t['primary_color'])) }">
        @csrf @method('PUT')
        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
            @foreach($themes as $key => $theme)
                <label class="card cursor-pointer overflow-hidden transition" :class="theme === @js($key) ? 'ring-2 ring-primary border-primary' : 'hover:border-slate-300'">
                    <input type="radio" name="theme" value="{{ $key }}" x-model="theme" class="sr-only">
                    <div class="relative aspect-[4/3] bg-slate-100">
                        @if(file_exists(public_path($theme['preview'])))
                            <img src="{{ asset($theme['preview']) }}?v={{ filemtime(public_path($theme['preview'])) }}" alt="{{ $theme['name'] }} önizleme" class="h-full w-full object-cover object-top">
                        @else
                            <div class="flex h-full items-center justify-center text-slate-300"><x-icon name="photo" class="h-10 w-10" /></div>
                        @endif
                        <span x-show="theme === @js($key)" x-cloak class="absolute top-3 right-3 flex h-7 w-7 items-center justify-center rounded-full bg-primary text-white shadow"><x-icon name="check" class="h-4 w-4" /></span>
                        @if($key === $current)<span class="absolute top-3 left-3 badge-green">Aktif</span>@endif
                    </div>
                    <div class="p-4">
                        <div class="flex items-center justify-between"><h2 class="font-semibold text-slate-900">{{ $theme['name'] }}</h2><span class="h-5 w-5 rounded-full border border-white shadow" style="background: {{ $theme['primary_color'] }}" title="Varsayılan renk"></span></div>
                        <p class="mt-1 text-xs leading-5 text-slate-500">{{ $theme['description'] }}</p>
                        <p class="mt-2 text-[11px] text-slate-400">Uygun: {{ implode(', ', $theme['suits']) }} · Yazı tipi: {{ config('themes.fonts.'.$theme['font']) }}</p>
                    </div>
                </label>
            @endforeach
        </div>
        @error('theme')<p class="form-error mt-2">{{ $message }}</p>@enderror

        <div class="card mt-6 max-w-3xl">
            <div class="card-header"><h2 class="font-semibold text-slate-900">Yazı Tipi ve Renk</h2></div>
            <div class="card-body space-y-5">
                <x-admin.select name="font" label="Yazı Tipi" :options="$fonts" :value="$currentFont" placeholder="Temanın varsayılanı" help="Boş bırakılırsa seçilen temanın yazı tipi kullanılır." />
                <x-admin.field-wrapper name="primary_color" label="Ana Renk (marka rengi)">
                    <div class="space-y-3" x-data="{ color: @js(old('primary_color', $primaryColor)) }">
                        <label class="flex items-center gap-2 text-sm text-slate-700"><input type="hidden" name="use_theme_color" value="0"><input type="checkbox" name="use_theme_color" value="1" x-model="useThemeColor" class="form-checkbox"> Temanın varsayılan rengini kullan (<span x-text="themeColors[theme]"></span>)</label>
                        <div class="flex items-center gap-3" x-show="!useThemeColor">
                            <input type="color" x-model="color" class="h-10 w-14 cursor-pointer rounded border border-slate-300">
                            <input type="text" name="primary_color" x-model="color" class="form-input !w-40 font-mono" pattern="#[0-9a-fA-F]{6}">
                            <div class="flex gap-1.5">@foreach(['#1d4ed8', '#7c3aed', '#0ea5e9', '#e11d48', '#0f766e', '#ea580c', '#b45309', '#4f46e5', '#0891b2', '#16a34a', '#0f172a'] as $swatch)<button type="button" class="h-7 w-7 rounded-full border border-white shadow ring-1 ring-slate-200" style="background: {{ $swatch }}" @click="color = '{{ $swatch }}'" aria-label="{{ $swatch }}"></button>@endforeach</div>
                        </div>
                    </div>
                </x-admin.field-wrapper>
            </div>
        </div>
        <div class="mt-6 max-w-3xl"><x-admin.form-actions :back="route('admin.settings.edit')"><a href="{{ route('home') }}" target="_blank" class="btn-secondary"><x-icon name="external" class="h-4 w-4" /> Siteyi Gör</a></x-admin.form-actions></div>
    </form>
@endsection
