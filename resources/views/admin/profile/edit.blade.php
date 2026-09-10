@extends('layouts.admin')
@section('title', 'Profilim')
@section('content')
    <x-admin.page-header title="Profilim" :subtitle="$user->role->label()" />
    <div class="grid max-w-4xl gap-6 md:grid-cols-2">
        <form method="post" action="{{ route('admin.profile.update') }}" class="card">
            @csrf @method('PUT')
            <div class="card-header"><h2 class="font-semibold text-slate-900">Hesap Bilgileri</h2></div>
            <div class="card-body space-y-4">
                <x-admin.input name="name" label="Ad Soyad" :value="$user->name" required />
                <x-admin.input name="email" label="E-posta" type="email" :value="$user->email" required />
                <button type="submit" class="btn-primary">Kaydet</button>
            </div>
        </form>
        <form method="post" action="{{ route('admin.profile.password') }}" class="card">
            @csrf @method('PUT')
            <div class="card-header"><h2 class="font-semibold text-slate-900">Şifre Değiştir</h2></div>
            <div class="card-body space-y-4">
                <x-admin.input name="current_password" label="Mevcut Şifre" type="password" required autocomplete="current-password" />
                <x-admin.input name="password" label="Yeni Şifre" type="password" required autocomplete="new-password" />
                <x-admin.input name="password_confirmation" label="Yeni Şifre (Tekrar)" type="password" required autocomplete="new-password" />
                <button type="submit" class="btn-primary">Şifreyi Güncelle</button>
            </div>
        </form>
    </div>
@endsection
