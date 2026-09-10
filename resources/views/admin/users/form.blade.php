@extends('layouts.admin')
@section('title', $user->exists ? 'Kullanıcı Düzenle' : 'Yeni Kullanıcı')
@section('content')
    <x-admin.page-header :title="$user->exists ? 'Kullanıcı Düzenle' : 'Yeni Kullanıcı'" :subtitle="$user->exists ? $user->email : null" />
    <form method="post" action="{{ $user->exists ? route('admin.users.update', $user) : route('admin.users.store') }}" class="max-w-2xl">
        @csrf
        @if($user->exists)@method('PUT')@endif
        <div class="card"><div class="card-body space-y-4">
            <x-admin.input name="name" label="Ad Soyad" :value="$user->name" required maxlength="100" />
            <x-admin.input name="email" label="E-posta" type="email" :value="$user->email" required maxlength="190" />
            <div class="grid gap-4 sm:grid-cols-2">
                <x-admin.input name="password" label="Şifre" type="password" :required="!$user->exists" autocomplete="new-password" :help="$user->exists ? 'Değiştirmek istemiyorsanız boş bırakın.' : 'En az 8 karakter.'" />
                <x-admin.input name="password_confirmation" label="Şifre (Tekrar)" type="password" :required="!$user->exists" autocomplete="new-password" />
            </div>
            @if($user->exists && $user->is(auth()->user()))
                <x-admin.alert type="info">Kendi rolünüzü ve durumunuzu bu ekrandan değiştiremezsiniz.</x-admin.alert>
                <input type="hidden" name="role" value="{{ $user->role->value }}">
                <input type="hidden" name="is_active" value="1">
            @else
                <x-admin.select name="role" label="Rol" :options="$roles" :value="$user->role?->value" required />
                <x-admin.checkbox name="is_active" label="Hesap aktif" :checked="$user->is_active" help="Pasif hesaplar giriş yapamaz." />
            @endif
        </div></div>
        <div class="mt-6"><x-admin.form-actions :back="route('admin.users.index')" /></div>
    </form>
@endsection
