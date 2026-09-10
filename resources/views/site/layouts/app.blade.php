<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @hasSection('seo')
        @yield('seo')
    @else
        <x-seo />
    @endif
    @php($primary = setting('primary_color', '#1d4ed8'))
    <style>
        :root {
            --color-primary: {{ $primary }};
            --color-primary-dark: color-mix(in srgb, {{ $primary }}, #000 22%);
            --color-primary-light: color-mix(in srgb, {{ $primary }}, #fff 88%);
        }
    </style>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family={{ $template->fonts() }}&display=swap" rel="stylesheet">
    @if(setting('favicon'))
        <link rel="icon" href="{{ upload_url(setting('favicon')) }}">
    @else
        <link rel="icon" href="{{ asset('favicon.ico') }}">
    @endif
    @vite($template->assets())
    @if(setting('google_analytics_id'))
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ setting('google_analytics_id') }}"></script>
        <script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','{{ setting('google_analytics_id') }}');</script>
    @endif
    @if(request()->routeIs('home'))
        <script type="application/ld+json">{!! organization_schema() !!}</script>
    @endif
    {!! setting('head_scripts') !!}
    @stack('head')
</head>
<body class="flex min-h-screen flex-col" x-data="siteHeader">
    @include('template::partials.header')

    <main class="flex-1">
        @yield('content')
    </main>

    @include('template::partials.footer')

    @if(setting('whatsapp'))
        <a href="https://wa.me/{{ preg_replace('/\D+/', '', setting('whatsapp')) }}" target="_blank" rel="noopener"
           class="fixed right-5 bottom-5 z-40 flex h-14 w-14 items-center justify-center rounded-full bg-[#25D366] text-white shadow-lg transition hover:scale-105"
           aria-label="WhatsApp ile iletişime geçin">
            <x-icon name="whatsapp" class="h-7 w-7" />
        </a>
    @endif
    @stack('scripts')
</body>
</html>
