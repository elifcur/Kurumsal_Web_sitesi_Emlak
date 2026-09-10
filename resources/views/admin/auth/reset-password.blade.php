@extends('layouts.admin-guest')
@section('title', 'Yeni Şifre Belirle')
@section('content')
    <h1 class="mb-6 text-xl font-bold text-slate-900">Yeni Şifre Belirle</h1>
    <form method="post" action="{{ route('admin.password.update') }}" class="space-y-5">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <x-admin.input name="email" label="E-posta" type="email" :value="$email" required />
        <x-admin.input name="password" label="Yeni Şifre" type="password" required autocomplete="new-password" />
        <x-admin.input name="password_confirmation" label="Yeni Şifre (Tekrar)" type="password" required autocomplete="new-password" />
        <button type="submit" class="btn-primary w-full">Şifreyi Güncelle</button>
    </form>
@endsection
