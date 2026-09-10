@props(['name', 'label' => null, 'help' => null, 'required' => false])
<div {{ $attributes->merge(['class' => '']) }}>
    @if($label)
        <label for="{{ $attributes->get('id', $name) }}" class="form-label">{{ $label }} @if($required)<span class="text-red-500">*</span>@endif</label>
    @endif
    {{ $slot }}
    @if($help)<p class="form-help">{{ $help }}</p>@endif
    @error(str_replace(['[', ']'], ['.', ''], $name))<p class="form-error">{{ $message }}</p>@enderror
</div>
