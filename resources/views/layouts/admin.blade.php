<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Yönetim') | {{ setting('site_name', config('app.name')) }}</title>
    @vite(['resources/css/admin.css', 'resources/js/admin.js'])
    <script>window.ADMIN_MEDIA_URL = @js(route('admin.media.picker'));</script>
</head>
<body x-data="adminShell" class="min-h-screen">
    {{-- Sidebar --}}
    <div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-30 bg-slate-900/50 lg:hidden" @click="sidebarOpen = false"></div>
    <aside class="fixed inset-y-0 left-0 z-40 flex w-64 -translate-x-full flex-col bg-slate-900 transition-transform lg:translate-x-0" :class="sidebarOpen && '!translate-x-0'">
        <div class="flex h-16 shrink-0 items-center gap-2 border-b border-slate-800 px-5 text-white">
            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary text-sm font-bold">{{ mb_substr(setting('site_name', 'C'), 0, 1) }}</span>
            <span class="truncate font-semibold">{{ setting('site_name', config('app.name')) }}</span>
        </div>
        <nav class="flex-1 overflow-y-auto px-3 py-4">
            <a href="{{ route('admin.dashboard') }}" @class(['sidebar-link', 'sidebar-link-active' => request()->routeIs('admin.dashboard')])><x-icon name="dashboard" /> Dashboard</a>

            <p class="sidebar-heading">İçerik</p>
            @can('manage-pages')<a href="{{ route('admin.pages.index') }}" @class(['sidebar-link', 'sidebar-link-active' => request()->routeIs('admin.pages.*')])><x-icon name="document" /> Sayfalar</a>@endcan
            @if($template->supports('services'))@can('manage-services')<a href="{{ route('admin.services.index') }}" @class(['sidebar-link', 'sidebar-link-active' => request()->routeIs('admin.services.*')])><x-icon name="briefcase" /> {{ setting('services_label', 'Hizmetler') }}</a>@endcan @endif
            @if($template->supports('products'))@can('manage-products')
                <a href="{{ route('admin.products.index') }}" @class(['sidebar-link', 'sidebar-link-active' => request()->routeIs('admin.products.*')])><x-icon name="box" /> {{ setting('products_label', 'Ürünler') }}</a>
            @endcan @endif
            @if($template->supports('products') || $template->supports('blog'))@can('manage-products')
                <a href="{{ route('admin.categories.index') }}" @class(['sidebar-link', 'sidebar-link-active' => request()->routeIs('admin.categories.*')])><x-icon name="tag" /> Kategoriler</a>
            @endcan @endif
            @if($template->supports('blog'))@can('manage-posts')<a href="{{ route('admin.posts.index') }}" @class(['sidebar-link', 'sidebar-link-active' => request()->routeIs('admin.posts.*')])><x-icon name="newspaper" /> {{ setting('blog_label', 'Blog') }}</a>@endcan @endif
            @can('manage-media')<a href="{{ route('admin.media.index') }}" @class(['sidebar-link', 'sidebar-link-active' => request()->routeIs('admin.media.*')])><x-icon name="photo" /> Medya</a>@endcan
            @can('manage-messages')
                <a href="{{ route('admin.messages.index') }}" @class(['sidebar-link', 'sidebar-link-active' => request()->routeIs('admin.messages.*')])><x-icon name="inbox" /> Mesajlar
                    @if($unreadMessages > 0)<span class="ml-auto rounded-full bg-red-500 px-2 py-0.5 text-[11px] font-semibold text-white">{{ $unreadMessages }}</span>@endif
                </a>
            @endcan

            @if($templateMenu !== [])
                <p class="sidebar-heading">{{ $template->name() }}</p>
                @foreach($templateMenu as $item)
                    <a href="{{ route($item['route']) }}" @class(['sidebar-link', 'sidebar-link-active' => request()->routeIs($item['pattern'] ?? $item['route'])])><x-icon :name="$item['icon'] ?? 'puzzle'" /> {{ $item['label'] }}</a>
                @endforeach
            @endif

            @can('manage-extras')
                @if($template->supports('team') || $template->supports('testimonials') || $template->supports('faqs') || $template->supports('partners'))
                    <p class="sidebar-heading">Ek İçerik</p>
                    @if($template->supports('team'))<a href="{{ route('admin.team.index') }}" @class(['sidebar-link', 'sidebar-link-active' => request()->routeIs('admin.team.*')])><x-icon name="users" /> Ekip</a>@endif
                    @if($template->supports('testimonials'))<a href="{{ route('admin.testimonials.index') }}" @class(['sidebar-link', 'sidebar-link-active' => request()->routeIs('admin.testimonials.*')])><x-icon name="star" /> Müşteri Yorumları</a>@endif
                    @if($template->supports('faqs'))<a href="{{ route('admin.faqs.index') }}" @class(['sidebar-link', 'sidebar-link-active' => request()->routeIs('admin.faqs.*')])><x-icon name="info" /> Sık Sorulan Sorular</a>@endif
                    @if($template->supports('partners'))<a href="{{ route('admin.partners.index') }}" @class(['sidebar-link', 'sidebar-link-active' => request()->routeIs('admin.partners.*')])><x-icon name="globe" /> Partnerler</a>@endif
                @endif
            @endcan

            @canany(['manage-settings', 'manage-users'])
                <p class="sidebar-heading">Sistem</p>
                @can('manage-settings')<a href="{{ route('admin.settings.edit') }}" @class(['sidebar-link', 'sidebar-link-active' => request()->routeIs('admin.settings.*')])><x-icon name="settings" /> Site Ayarları</a>@endcan
                @can('manage-users')<a href="{{ route('admin.users.index') }}" @class(['sidebar-link', 'sidebar-link-active' => request()->routeIs('admin.users.*')])><x-icon name="users" /> Kullanıcılar</a>@endcan
            @endcanany
        </nav>
        <div class="border-t border-slate-800 p-3">
            <a href="{{ route('home') }}" target="_blank" class="sidebar-link"><x-icon name="external" /> Siteyi Görüntüle</a>
        </div>
    </aside>

    {{-- Main --}}
    <div class="flex min-h-screen flex-col lg:pl-64">
        <header class="sticky top-0 z-20 flex h-16 items-center justify-between border-b border-slate-200 bg-white px-4 sm:px-6">
            <button type="button" class="rounded-lg p-2 text-slate-600 hover:bg-slate-100 lg:hidden" @click="sidebarOpen = true" aria-label="Menü"><x-icon name="menu" class="h-6 w-6" /></button>
            <div class="hidden text-sm text-slate-500 lg:block">@yield('title', 'Yönetim')</div>
            <div class="relative" @click.outside="userMenu = false">
                <button type="button" class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-sm hover:bg-slate-100" @click="userMenu = !userMenu">
                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-primary-light text-sm font-semibold text-primary">{{ mb_substr(auth()->user()->name, 0, 1) }}</span>
                    <span class="hidden text-left sm:block"><span class="block font-medium text-slate-800">{{ auth()->user()->name }}</span><span class="block text-xs text-slate-500">{{ auth()->user()->role->label() }}</span></span>
                    <x-icon name="chevron-down" class="h-4 w-4 text-slate-400" />
                </button>
                <div x-show="userMenu" x-cloak x-transition class="absolute right-0 mt-2 w-48 rounded-lg border border-slate-200 bg-white py-1 shadow-lg">
                    <a href="{{ route('admin.profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50"><x-icon name="user" class="h-4 w-4" /> Profilim</a>
                    <form method="post" action="{{ route('admin.logout') }}">@csrf<button type="submit" class="flex w-full items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-slate-50"><x-icon name="logout" class="h-4 w-4" /> Çıkış Yap</button></form>
                </div>
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            @if(session('success'))<x-admin.alert type="success" class="mb-6">{{ session('success') }}</x-admin.alert>@endif
            @if(session('error'))<x-admin.alert type="error" class="mb-6">{{ session('error') }}</x-admin.alert>@endif
            @if($errors->any())<x-admin.alert type="error" class="mb-6">Lütfen formdaki hataları düzeltin.</x-admin.alert>@endif
            @yield('content')
        </main>
    </div>

    <x-admin.media-picker />
</body>
</html>
