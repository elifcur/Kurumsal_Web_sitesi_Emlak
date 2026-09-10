@props(['name', 'label' => null, 'value' => [], 'help' => null, 'labelPlaceholder' => 'Özellik', 'valuePlaceholder' => 'Değer'])
@php($rows = collect(old($name, $value ?? []))->map(fn ($r) => ['label' => $r['label'] ?? '', 'value' => $r['value'] ?? ''])->values())
<x-admin.field-wrapper :name="$name" :label="$label" :help="$help">
    <div x-data="repeaterField(@js($rows))" class="space-y-2">
        <template x-for="(row, index) in rows" :key="index">
            <div class="flex items-center gap-2">
                <input type="text" :name="'{{ $name }}[' + index + '][label]'" x-model="row.label" class="form-input" placeholder="{{ $labelPlaceholder }}">
                <input type="text" :name="'{{ $name }}[' + index + '][value]'" x-model="row.value" class="form-input" placeholder="{{ $valuePlaceholder }}">
                <button type="button" @click="remove(index)" class="btn-ghost btn-sm text-red-600" aria-label="Sil"><x-icon name="trash" class="h-4 w-4" /></button>
            </div>
        </template>
        <button type="button" @click="add()" class="btn-secondary btn-sm"><x-icon name="plus" class="h-4 w-4" /> Satır Ekle</button>
    </div>
</x-admin.field-wrapper>
