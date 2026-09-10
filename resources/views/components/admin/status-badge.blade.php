@props(['active' => false, 'on' => 'Aktif', 'off' => 'Pasif'])
<span class="{{ $active ? 'badge-green' : 'badge-gray' }}">{{ $active ? $on : $off }}</span>
