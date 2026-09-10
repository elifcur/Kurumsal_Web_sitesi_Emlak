@props(['name', 'label' => null, 'type' => 'text', 'value' => null, 'help' => null, 'required' => false, 'wrapperClass' => ''])
<x-admin.field-wrapper :name="$name" :label="$label" :help="$help" :required="$required" :class="$wrapperClass">
    <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}" value="{{ $type === 'password' ? '' : old($name, $value) }}" @required($required) {{ $attributes->merge(['class' => 'form-input']) }}>
</x-admin.field-wrapper>
