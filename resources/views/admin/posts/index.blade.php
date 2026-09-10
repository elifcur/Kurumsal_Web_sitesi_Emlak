@extends('layouts.admin')
@section('title', 'Blog')
@section('content')
    <x-admin.page-header title="Blog / Haberler" subtitle="Yazıları, kategorileri ve etiketleri yönetin.">
        <x-slot:actions><a href="{{ route('admin.categories.index', ['type' => 'post']) }}" class="btn-secondary"><x-icon name="tag" class="h-4 w-4" /> Kategoriler</a><a href="{{ route('admin.posts.create') }}" class="btn-primary"><x-icon name="plus" class="h-4 w-4" /> Yeni Yazı</a></x-slot:actions>
    </x-admin.page-header>

    <div class="card">
        <div class="card-header">
            <x-admin.search placeholder="Yazı ara...">
                <select name="category" class="form-input !w-44"><option value="">Tüm kategoriler</option>@foreach($categories as $id => $name)<option value="{{ $id }}" @selected(request('category') == $id)>{{ $name }}</option>@endforeach</select>
                <select name="status" class="form-input !w-36"><option value="">Tüm durumlar</option>@foreach(\App\Enums\PostStatus::options() as $v => $l)<option value="{{ $v }}" @selected(request('status') === $v)>{{ $l }}</option>@endforeach</select>
            </x-admin.search>
        </div>
        @if($posts->isEmpty())
            <x-admin.empty title="Yazı bulunamadı" icon="newspaper" />
        @else
            <div class="overflow-x-auto"><table class="table">
                <thead><tr><th>Başlık</th><th>Kategori</th><th>Yazar</th><th>Yayın Tarihi</th><th>Durum</th><th>Görüntülenme</th><th class="text-right">İşlem</th></tr></thead>
                <tbody>
                @foreach($posts as $post)
                    <tr>
                        <td><a href="{{ route('admin.posts.edit', $post) }}" class="font-medium text-slate-900 hover:text-primary">{{ $post->title }}</a></td>
                        <td>{{ $post->category?->name ?? '—' }}</td>
                        <td>{{ $post->author?->name ?? '—' }}</td>
                        <td class="whitespace-nowrap text-xs text-slate-500">{{ $post->published_at?->format('d.m.Y H:i') ?? '—' }}</td>
                        <td>
                            @if($post->isPublished())<span class="badge-green">Yayında</span>
                            @elseif($post->status === \App\Enums\PostStatus::Published)<span class="badge-blue">Zamanlanmış</span>
                            @else<span class="badge-yellow">Taslak</span>@endif
                        </td>
                        <td>{{ $post->views }}</td>
                        <td class="text-right whitespace-nowrap">
                            @if($post->isPublished())<a href="{{ $post->url() }}" target="_blank" class="btn-ghost btn-sm"><x-icon name="external" class="h-4 w-4" /></a>@endif
                            <a href="{{ route('admin.posts.edit', $post) }}" class="btn-ghost btn-sm"><x-icon name="pencil" class="h-4 w-4" /></a>
                            <x-admin.delete-button :action="route('admin.posts.destroy', $post)" />
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table></div>
            <div class="px-5 py-4">{{ $posts->links() }}</div>
        @endif
    </div>
@endsection
