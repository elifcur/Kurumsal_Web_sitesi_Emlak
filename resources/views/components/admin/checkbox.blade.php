@props(['name', 'label', 'checked' => false, 'help' => null])
<div {{ $attributes->merge(['class' => '']) }}>
    <label class="flex cursor-pointer items-start gap-3">
        <input type="hidden" name="{{ $name }}" value="0">
        <input type="checkbox" name="{{ $name }}" id="{{ $name }}" value="1" class="form-checkbox mt-0.5" @checked(old($name, $checked))>
        <span>
            <span class="text-sm font-medium text-slate-700">{{ $label }}</span>
            @if($help)<span class="block text-xs text-slate-500">{{ $help }}</span>@endif
        </span>
    </label>
    @error($name)<p class="form-error">{{ $message }}</p>@enderror
</div>
