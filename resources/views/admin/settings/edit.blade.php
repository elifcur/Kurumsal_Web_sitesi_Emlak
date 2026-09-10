@extends('layouts.admin')
@section('title', 'Site Ayarları')
@section('content')
    <x-admin.page-header title="Site Ayarları" subtitle="Sitenin genel bilgileri, iletişim ve SEO ayarları.">
        <x-slot:actions>
            <form method="post" action="{{ route('admin.settings.clear-cache') }}">@csrf<button type="submit" class="btn-secondary"><x-icon name="cog" class="h-4 w-4" /> Önbelleği Temizle</button></form>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="mb-6 flex flex-wrap gap-2">
        @foreach($groups as $key => $group)
            <a href="{{ route('admin.settings.edit', ['tab' => $key]) }}" @class(['rounded-lg px-4 py-2 text-sm font-medium', 'bg-primary text-white' => $tab === $key, 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50' => $tab !== $key])>{{ $group['label'] }}</a>
        @endforeach
    </div>

    <form method="post" action="{{ route('admin.settings.update') }}" class="max-w-3xl">
        @csrf @method('PUT')
        <input type="hidden" name="tab" value="{{ $tab }}">
        <div class="card"><div class="card-body space-y-5">
            @foreach($groups[$tab]['fields'] as $key => $field)
                @php($value = $values[$key] ?? null)
                @switch($field['type'])
                    @case('textarea')
                        <x-admin.textarea :name="$key" :label="$field['label']" :value="$value" :help="$field['help'] ?? null" rows="3" />
                        @break
                    @case('code')
                        <x-admin.textarea :name="$key" :label="$field['label']" :value="$value" :help="$field['help'] ?? null" rows="6" class="font-mono !text-xs" />
                        @break
                    @case('image')
                        <x-admin.image :name="$key" :label="$field['label']" :value="$value" :help="$field['help'] ?? null" />
                        @break
                    @case('boolean')
                        <x-admin.checkbox :name="$key" :label="$field['label']" :checked="($value ?? '1') === '1'" :help="$field['help'] ?? null" />
                        @break
                    @case('color')
                        <x-admin.field-wrapper :name="$key" :label="$field['label']" :help="$field['help'] ?? null">
                            <div class="flex items-center gap-3" x-data="{ color: @js($value ?: '#1d4ed8') }">
                                <input type="color" x-model="color" class="h-10 w-14 cursor-pointer rounded border border-slate-300">
                                <input type="text" name="{{ $key }}" id="{{ $key }}" x-model="color" class="form-input !w-40 font-mono" pattern="#[0-9a-fA-F]{6}">
                            </div>
                        </x-admin.field-wrapper>
                        @break
                    @default
                        <x-admin.input :name="$key" :label="$field['label']" :type="$field['type']" :value="$value" :help="$field['help'] ?? null" :required="in_array('required', $field['rules'] ?? [], true)" />
                @endswitch
            @endforeach
        </div></div>
        <div class="mt-6"><x-admin.form-actions :back="route('admin.dashboard')" /></div>
    </form>
@endsection
