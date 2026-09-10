@extends('layouts.admin-guest')
@section('title', 'Giriş Yap')
@section('content')
    <h1 class="mb-6 text-xl font-bold text-slate-900">Giriş Yap</h1>
    @if(session('status'))<x-admin.alert type="success" class="mb-5">{{ session('status') }}</x-admin.alert>@endif
    <form method="post" action="{{ route('admin.login.store') }}" class="space-y-5">
        @csrf
        <x-admin.input name="email" label="E-posta" type="email" required autofocus autocomplete="username" />
        <x-admin.input name="password" label="Şifre" type="password" required autocomplete="current-password" />
        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 text-sm text-slate-600"><input type="checkbox" name="remember" value="1" class="form-checkbox"> Beni hatırla</label>
            <a href="{{ route('admin.password.request') }}" class="text-sm text-primary hover:underline">Şifremi unuttum</a>
        </div>
        <button type="submit" class="btn-primary w-full">Giriş Yap</button>
    </form>
@endsection
