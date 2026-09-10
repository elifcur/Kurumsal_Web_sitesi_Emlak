@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')
    <x-admin.page-header title="Dashboard" :subtitle="'Hoş geldiniz, '.auth()->user()->name" />

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        @foreach($stats as $stat)
            <a href="{{ isset($stat['route']) ? route($stat['route']) : '#' }}" class="card flex items-center gap-4 p-5 transition hover:border-primary/50">
                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-primary-light text-primary"><x-icon :name="$stat['icon']" class="h-6 w-6" /></span>
                <span>
                    <span class="block text-2xl font-bold text-slate-900">{{ $stat['value'] }}</span>
                    <span class="block text-sm font-medium text-slate-700">{{ $stat['label'] }}</span>
                    <span class="block text-xs text-slate-500">{{ $stat['sub'] }}</span>
                </span>
            </a>
        @endforeach
    </div>

    <div class="mt-8 grid gap-6 xl:grid-cols-3">
        @can('manage-messages')
        <div class="card xl:col-span-2">
            <div class="card-header"><h2 class="font-semibold text-slate-900">Son Mesajlar</h2><a href="{{ route('admin.messages.index') }}" class="text-sm text-primary hover:underline">Tümü</a></div>
            @if($recentMessages->isEmpty())
                <x-admin.empty title="Henüz mesaj yok" />
            @else
                <div class="overflow-x-auto"><table class="table">
                    <thead><tr><th>Gönderen</th><th>Konu</th><th>Tür</th><th>Tarih</th><th></th></tr></thead>
                    <tbody>
                    @foreach($recentMessages as $message)
                        <tr @class(['font-semibold' => !$message->is_read])>
                            <td>{{ $message->name }}<span class="block text-xs font-normal text-slate-500">{{ $message->email }}</span></td>
                            <td class="max-w-[220px] truncate">{{ $message->subject ?: \Illuminate\Support\Str::limit($message->message, 40) }}</td>
                            <td><span class="badge-blue">{{ $message->typeLabel() }}</span></td>
                            <td class="whitespace-nowrap text-xs text-slate-500">{{ $message->created_at->diffForHumans() }}</td>
                            <td class="text-right"><a href="{{ route('admin.messages.show', $message) }}" class="btn-ghost btn-sm"><x-icon name="eye" class="h-4 w-4" /></a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table></div>
            @endif
        </div>
        @endcan

        <div class="space-y-6">
            @can('manage-posts')
            <div class="card">
                <div class="card-header"><h2 class="font-semibold text-slate-900">Son Yazılar</h2><a href="{{ route('admin.posts.create') }}" class="btn-primary btn-sm"><x-icon name="plus" class="h-4 w-4" /> Yeni</a></div>
                <ul class="divide-y divide-slate-100">
                    @forelse($recentPosts as $post)
                        <li class="flex items-center justify-between gap-3 px-5 py-3 text-sm">
                            <a href="{{ route('admin.posts.edit', $post) }}" class="truncate font-medium text-slate-800 hover:text-primary">{{ $post->title }}</a>
                            <span class="{{ $post->isPublished() ? 'badge-green' : 'badge-yellow' }}">{{ $post->status->label() }}</span>
                        </li>
                    @empty
                        <li class="px-5 py-6 text-center text-sm text-slate-500">Henüz yazı yok.</li>
                    @endforelse
                </ul>
            </div>
            @endcan
            @can('manage-pages')
            <div class="card">
                <div class="card-header"><h2 class="font-semibold text-slate-900">Son Güncellenen Sayfalar</h2></div>
                <ul class="divide-y divide-slate-100">
                    @forelse($recentPages as $page)
                        <li class="flex items-center justify-between gap-3 px-5 py-3 text-sm">
                            <a href="{{ route('admin.pages.edit', $page) }}" class="truncate font-medium text-slate-800 hover:text-primary">{{ $page->title }}</a>
                            <span class="text-xs text-slate-500">{{ $page->updated_at->diffForHumans() }}</span>
                        </li>
                    @empty
                        <li class="px-5 py-6 text-center text-sm text-slate-500">Henüz sayfa yok.</li>
                    @endforelse
                </ul>
            </div>
            @endcan
            <div class="card p-5 text-sm">
                <h2 class="font-semibold text-slate-900">Sistem</h2>
                <dl class="mt-3 space-y-1.5 text-slate-600">
                    <div class="flex justify-between"><dt>Şablon</dt><dd class="font-medium text-slate-900">{{ $template->name() }} <span class="text-xs text-slate-400">({{ $template->key() }})</span></dd></div>
                    <div class="flex justify-between"><dt>Kullanıcılar</dt><dd class="font-medium text-slate-900">{{ $userCount }}</dd></div>
                    <div class="flex justify-between"><dt>Laravel</dt><dd class="font-medium text-slate-900">{{ app()->version() }}</dd></div>
                    <div class="flex justify-between"><dt>PHP</dt><dd class="font-medium text-slate-900">{{ PHP_VERSION }}</dd></div>
                    <div class="flex justify-between"><dt>Ortam</dt><dd class="font-medium text-slate-900">{{ app()->environment() }} @if(config('app.debug'))<span class="badge-red ml-1">debug açık</span>@endif</dd></div>
                </dl>
            </div>
        </div>
    </div>
@endsection
