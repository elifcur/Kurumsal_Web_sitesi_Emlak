@props(['name', 'label' => null, 'value' => null, 'help' => null])
<x-admin.field-wrapper :name="$name" :label="$label" :help="$help">
    <div x-data="richEditor('{{ $name }}')" wire:ignore>
        <textarea name="{{ $name }}" id="{{ $name }}" x-ref="input" class="hidden">{{ old($name, $value) }}</textarea>
        <div x-ref="editor" class="bg-white"></div>
    </div>
</x-admin.field-wrapper>
