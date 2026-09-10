@props(['name', 'label' => 'Galeri', 'value' => [], 'help' => null])
@php($items = collect(old($name, $value ?? []))->filter()->map(fn ($p) => ['path' => $p, 'url' => upload_url($p)])->values())
<x-admin.field-wrapper :name="$name" :label="$label" :help="$help">
    <div x-data="galleryField(@js($items))">
        <div class="flex flex-wrap gap-3">
            <template x-for="(item, index) in items" :key="index">
                <div class="group relative h-24 w-24 overflow-hidden rounded-lg border border-slate-200">
                    <img :src="item.url" alt="" class="h-full w-full object-cover">
                    <input type="hidden" :name="'{{ $name }}[]'" :value="item.path">
                    <button type="button" @click="remove(index)" class="absolute top-1 right-1 rounded-full bg-white/90 p-1 text-red-600 shadow opacity-0 transition group-hover:opacity-100" aria-label="Kaldır"><x-icon name="x" class="h-3.5 w-3.5" /></button>
                </div>
            </template>
            <button type="button" @click="add()" class="flex h-24 w-24 flex-col items-center justify-center gap-1 rounded-lg border border-dashed border-slate-300 text-xs text-slate-500 hover:border-primary hover:text-primary"><x-icon name="plus" class="h-5 w-5" /> Ekle</button>
        </div>
    </div>
</x-admin.field-wrapper>
