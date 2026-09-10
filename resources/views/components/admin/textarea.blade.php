@props(['name', 'label' => null, 'value' => null, 'help' => null, 'required' => false, 'rows' => 3, 'wrapperClass' => ''])
<x-admin.field-wrapper :name="$name" :label="$label" :help="$help" :required="$required" :class="$wrapperClass">
    <textarea name="{{ $name }}" id="{{ $name }}" rows="{{ $rows }}" @required($required) {{ $attributes->merge(['class' => 'form-input']) }}>{{ old($name, $value) }}</textarea>
</x-admin.field-wrapper>
