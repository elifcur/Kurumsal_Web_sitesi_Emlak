<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Giriş') | {{ setting('site_name', config('app.name')) }} Yönetim</title>
    @vite(['resources/css/admin.css', 'resources/js/admin.js'])
</head>
<body class="flex min-h-screen items-center justify-center bg-slate-900 p-4">
    <div class="w-full max-w-md">
        <div class="mb-8 text-center text-white">
            @if(setting('logo'))
                <img src="{{ upload_url(setting('logo')) }}" alt="{{ setting('site_name') }}" class="mx-auto h-12 w-auto brightness-0 invert">
            @else
                <span class="text-2xl font-bold">{{ setting('site_name', config('app.name')) }}</span>
            @endif
            <p class="mt-2 text-sm text-slate-400">Yönetim Paneli</p>
        </div>
        <div class="card p-8">
            @yield('content')
        </div>
        <p class="mt-6 text-center text-xs text-slate-500"><a href="{{ route('home') }}" class="hover:text-slate-300">&larr; Siteye dön</a></p>
    </div>
</body>
</html>
