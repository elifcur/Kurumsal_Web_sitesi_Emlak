@extends('layouts.admin')
@section('title', 'Medya')
@section('content')
    <x-admin.page-header title="Medya Kütüphanesi" subtitle="Görsel ve dosyalarınızı yönetin.">
        <x-slot:actions>
            <form method="post" action="{{ route('admin.media.store') }}" enctype="multipart/form-data" class="flex items-center gap-2">
                @csrf
                <label class="btn-primary cursor-pointer"><x-icon name="upload" class="h-4 w-4" /> Dosya Yükle <input type="file" name="files[]" multiple accept="image/*,.pdf" class="hidden" onchange="this.form.submit()"></label>
            </form>
        </x-slot:actions>
    </x-admin.page-header>
    @error('files.*')<x-admin.alert type="error" class="mb-4">{{ $message }}</x-admin.alert>@enderror
    <p class="mb-4 text-xs text-slate-500">İzin verilen türler: {{ implode(', ', [...config('cms.uploads.image_mimes'), ...config('cms.uploads.document_mimes')]) }} · En fazla {{ round(config('cms.uploads.max_kb') / 1024, 1) }} MB · Görseller otomatik olarak {{ config('cms.uploads.max_width') }}px genişliğe küçültülür.</p>

    <div class="card" x-data="{ selected: null }">
        <div class="card-header">
            <x-admin.search placeholder="Dosya ara...">
                <select name="type" class="form-input !w-36"><option value="">Tüm türler</option><option value="image" @selected(request('type') === 'image')>Görseller</option><option value="document" @selected(request('type') === 'document')>Belgeler</option></select>
            </x-admin.search>
            <span class="text-sm text-slate-500">{{ $media->total() }} dosya</span>
        </div>
        @if($media->isEmpty())
            <x-admin.empty title="Henüz dosya yüklenmedi" icon="photo" />
        @else
            <div class="grid grid-cols-2 gap-4 p-5 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-6">
                @foreach($media as $item)
                    <div class="group relative">
                        <button type="button" @click="selected = selected === {{ $item->id }} ? null : {{ $item->id }}" class="block aspect-square w-full overflow-hidden rounded-lg border border-slate-200 bg-slate-50 transition hover:border-primary" :class="selected === {{ $item->id }} && 'ring-2 ring-primary'">
                            @if($item->isImage())
                                <img src="{{ $item->thumbUrl() }}" alt="{{ $item->alt ?? $item->name }}" class="h-full w-full object-cover" loading="lazy">
                            @else
                                <span class="flex h-full flex-col items-center justify-center gap-1 p-2 text-xs text-slate-500"><x-icon name="document" class="h-8 w-8" /><span class="line-clamp-2 break-all">{{ $item->file_name }}</span></span>
                            @endif
                        </button>
                        <p class="mt-1 truncate text-xs text-slate-600" title="{{ $item->name }}">{{ $item->name }}</p>
                        <p class="text-[11px] text-slate-400">{{ $item->humanSize() }}@if($item->width) · {{ $item->width }}×{{ $item->height }}@endif</p>

                        <div x-show="selected === {{ $item->id }}" x-cloak class="absolute top-0 left-0 z-10 w-72 rounded-lg border border-slate-200 bg-white p-4 shadow-xl">
                            <form method="post" action="{{ route('admin.media.update', $item) }}" class="space-y-3">
                                @csrf @method('PUT')
                                <x-admin.input name="name" label="Ad" :value="$item->name" required />
                                <x-admin.input name="alt" label="Alternatif Metin (alt)" :value="$item->alt" />
                                <div>
                                    <label class="form-label">URL</label>
                                    <input type="text" value="{{ $item->url() }}" readonly class="form-input !text-xs" onclick="this.select()">
                                </div>
                                <div class="flex items-center justify-between">
                                    <button type="submit" class="btn-primary btn-sm">Kaydet</button>
                                    <button type="button" class="btn-ghost btn-sm" @click="selected = null">Kapat</button>
                                </div>
                            </form>
                            <div class="mt-3 border-t border-slate-100 pt-3">
                                <x-admin.delete-button :action="route('admin.media.destroy', $item)" label="Dosyayı sil" message="Dosya kalıcı olarak silinecek. İçeriklerde kullanılıyorsa görsel kırılır. Devam edilsin mi?" />
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="px-5 py-4">{{ $media->links() }}</div>
        @endif
    </div>
@endsection
