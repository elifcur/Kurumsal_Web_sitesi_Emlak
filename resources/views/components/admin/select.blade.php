@props(['name', 'label' => null, 'options' => [], 'value' => null, 'help' => null, 'required' => false, 'placeholder' => null, 'wrapperClass' => ''])
<x-admin.field-wrapper :name="$name" :label="$label" :help="$help" :required="$required" :class="$wrapperClass">
    <select name="{{ $name }}" id="{{ $name }}" @required($required) {{ $attributes->merge(['class' => 'form-input']) }}>
        @if($placeholder)<option value="">{{ $placeholder }}</option>@endif
        @foreach($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" @selected((string) old($name, $value) === (string) $optionValue)>{{ $optionLabel }}</option>
        @endforeach
    </select>
</x-admin.field-wrapper>
