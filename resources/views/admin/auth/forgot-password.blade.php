@extends('layouts.admin-guest')
@section('title', 'Şifremi Unuttum')
@section('content')
    <h1 class="mb-2 text-xl font-bold text-slate-900">Şifremi Unuttum</h1>
    <p class="mb-6 text-sm text-slate-500">E-posta adresinizi girin, size şifre sıfırlama bağlantısı gönderelim.</p>
    @if(session('status'))<x-admin.alert type="success" class="mb-5">{{ session('status') }}</x-admin.alert>@endif
    <form method="post" action="{{ route('admin.password.email') }}" class="space-y-5">
        @csrf
        <x-admin.input name="email" label="E-posta" type="email" required autofocus />
        <button type="submit" class="btn-primary w-full">Bağlantı Gönder</button>
        <p class="text-center text-sm"><a href="{{ route('admin.login') }}" class="text-primary hover:underline">Giriş sayfasına dön</a></p>
    </form>
@endsection
