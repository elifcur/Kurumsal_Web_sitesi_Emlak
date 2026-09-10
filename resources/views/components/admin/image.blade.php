@props(['name', 'label' => 'Görsel', 'value' => null, 'help' => null])
@php($current = old($name, $value))
<x-admin.field-wrapper :name="$name" :label="$label" :help="$help">
    <div x-data="imageField(@js($current ?? ''), @js(upload_url($current)))" class="flex items-start gap-4">
        <input type="hidden" name="{{ $name }}" id="{{ $name }}" :value="path">
        <div class="flex h-24 w-32 shrink-0 items-center justify-center overflow-hidden rounded-lg border border-dashed border-slate-300 bg-slate-50">
            <template x-if="url"><img :src="url" alt="" class="h-full w-full object-cover"></template>
            <template x-if="!url"><x-icon name="photo" class="h-8 w-8 text-slate-300" /></template>
        </div>
        <div class="flex flex-col gap-2">
            <button type="button" class="btn-secondary btn-sm" @click="pick()"><x-icon name="photo" class="h-4 w-4" /> Görsel Seç</button>
            <button type="button" class="btn-ghost btn-sm text-red-600" x-show="path" @click="clear()"><x-icon name="x" class="h-4 w-4" /> Kaldır</button>
        </div>
    </div>
</x-admin.field-wrapper>
