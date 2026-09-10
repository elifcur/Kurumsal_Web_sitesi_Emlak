@extends('layouts.admin')
@section('title', 'Kullanıcılar')
@section('content')
    <x-admin.page-header title="Kullanıcılar" subtitle="Yönetim paneline erişebilen hesaplar ve yetkileri.">
        <x-slot:actions><a href="{{ route('admin.users.create') }}" class="btn-primary"><x-icon name="plus" class="h-4 w-4" /> Yeni Kullanıcı</a></x-slot:actions>
    </x-admin.page-header>

    <div class="card">
        <div class="card-header">
            <x-admin.search placeholder="Ad veya e-posta...">
                <select name="role" class="form-input !w-40"><option value="">Tüm roller</option>@foreach($roles as $v => $l)<option value="{{ $v }}" @selected(request('role') === $v)>{{ $l }}</option>@endforeach</select>
            </x-admin.search>
        </div>
        <div class="overflow-x-auto"><table class="table">
            <thead><tr><th>Kullanıcı</th><th>Rol</th><th>Durum</th><th>Son Giriş</th><th class="text-right">İşlem</th></tr></thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <td class="flex items-center gap-3"><span class="flex h-9 w-9 items-center justify-center rounded-full bg-primary-light text-sm font-semibold text-primary">{{ mb_substr($user->name, 0, 1) }}</span><span><span class="block font-medium text-slate-900">{{ $user->name }} @if($user->is(auth()->user()))<span class="badge-gray">siz</span>@endif</span><span class="block text-xs text-slate-500">{{ $user->email }}</span></span></td>
                    <td><span class="{{ $user->isSuperAdmin() ? 'badge-red' : ($user->role === \App\Enums\UserRole::Admin ? 'badge-blue' : 'badge-gray') }}">{{ $user->role->label() }}</span></td>
                    <td><x-admin.status-badge :active="$user->is_active" /></td>
                    <td class="whitespace-nowrap text-xs text-slate-500">{{ $user->last_login_at?->format('d.m.Y H:i') ?? '—' }}</td>
                    <td class="text-right whitespace-nowrap">
                        @if(!$user->isSuperAdmin() || auth()->user()->isSuperAdmin())
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn-ghost btn-sm"><x-icon name="pencil" class="h-4 w-4" /></a>
                            @unless($user->is(auth()->user()))<x-admin.delete-button :action="route('admin.users.destroy', $user)" />@endunless
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table></div>
        <div class="px-5 py-4">{{ $users->links() }}</div>
    </div>

    <div class="card mt-6">
        <div class="card-header"><h2 class="font-semibold text-slate-900">Rol Yetkileri</h2></div>
        <div class="overflow-x-auto"><table class="table">
            <thead><tr><th>Yetki</th>@foreach(\App\Enums\UserRole::cases() as $role)<th class="text-center">{{ $role->label() }}</th>@endforeach</tr></thead>
            <tbody>
            @foreach(config('cms.all_permissions') as $permission => $label)
                <tr><td>{{ $label }}</td>
                    @foreach(\App\Enums\UserRole::cases() as $role)
                        <td class="text-center">@if($role === \App\Enums\UserRole::SuperAdmin || in_array($permission, config('cms.permissions.'.$role->value, []), true))<x-icon name="check" class="mx-auto h-4 w-4 text-green-600" />@else<span class="text-slate-300">—</span>@endif</td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table></div>
    </div>
@endsection
