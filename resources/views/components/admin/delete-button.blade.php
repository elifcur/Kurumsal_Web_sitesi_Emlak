@props(['action', 'message' => 'Bu kaydı silmek istediğinize emin misiniz?', 'label' => null, 'class' => 'btn-ghost btn-sm text-red-600'])
<form method="post" action="{{ $action }}" class="inline" onsubmit="return confirmDelete(@js($message))">
    @csrf
    @method('DELETE')
    <button type="submit" class="{{ $class }}" title="Sil"><x-icon name="trash" class="h-4 w-4" /> {{ $label }}</button>
</form>
