@props(['value' => null, 'titleField' => 'title', 'prefix' => null])
<x-admin.field-wrapper name="slug" label="URL (slug)" help="Boş bırakılırsa başlıktan otomatik oluşturulur.">
    <div class="flex items-center">
        @if($prefix)<span class="rounded-l-lg border border-r-0 border-slate-300 bg-slate-50 px-3 py-2 text-sm text-slate-500">{{ $prefix }}</span>@endif
        <input type="text" name="slug" id="slug" x-model="slug" @input="locked = true" class="form-input {{ $prefix ? '!rounded-l-none' : '' }}" placeholder="otomatik">
    </div>
</x-admin.field-wrapper>
