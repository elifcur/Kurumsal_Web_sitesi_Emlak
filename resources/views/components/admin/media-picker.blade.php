<div x-data="mediaPicker" x-show="open" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 p-4" @keydown.escape.window="open = false">
    <div class="flex max-h-[85vh] w-full max-w-4xl flex-col rounded-xl bg-white shadow-2xl" @click.outside="open = false">
        <div class="flex items-center justify-between gap-4 border-b border-slate-200 px-5 py-3">
            <h3 class="font-semibold text-slate-900">Medya Seç</h3>
            <div class="flex items-center gap-2">
                <input type="search" x-model.debounce.400ms="search" @input="page = 1; load()" placeholder="Ara..." class="form-input !w-48">
                <label class="btn-primary btn-sm cursor-pointer"><x-icon name="upload" class="h-4 w-4" /> Yükle <input type="file" class="hidden" multiple accept="image/*,.pdf" @change="upload($event)"></label>
                <button type="button" class="btn-ghost btn-sm" @click="open = false" aria-label="Kapat"><x-icon name="x" class="h-5 w-5" /></button>
            </div>
        </div>
        <div class="flex-1 overflow-y-auto p-5">
            <div x-show="loading" class="py-10 text-center text-sm text-slate-500">Yükleniyor...</div>
            <div x-show="!loading && items.length === 0" class="py-10 text-center text-sm text-slate-500">Medya bulunamadı. Yeni dosya yükleyebilirsiniz.</div>
            <div x-show="!loading" class="grid grid-cols-3 gap-3 sm:grid-cols-4 md:grid-cols-6">
                <template x-for="item in items" :key="item.id">
                    <button type="button" @click="select(item)" class="group aspect-square overflow-hidden rounded-lg border border-slate-200 bg-slate-50 transition hover:border-primary hover:ring-2 hover:ring-primary/30" :title="item.name">
                        <template x-if="item.is_image"><img :src="item.thumb_url" :alt="item.name" class="h-full w-full object-cover"></template>
                        <template x-if="!item.is_image"><span class="flex h-full w-full flex-col items-center justify-center gap-1 p-2 text-xs text-slate-500"><x-icon name="document" class="h-8 w-8" /><span class="line-clamp-2 break-all" x-text="item.name"></span></span></template>
                    </button>
                </template>
            </div>
        </div>
        <div class="flex items-center justify-between border-t border-slate-200 px-5 py-3 text-sm" x-show="lastPage > 1">
            <button type="button" class="btn-secondary btn-sm" :disabled="page <= 1" @click="page--; load()">Önceki</button>
            <span class="text-slate-500">Sayfa <span x-text="page"></span> / <span x-text="lastPage"></span></span>
            <button type="button" class="btn-secondary btn-sm" :disabled="page >= lastPage" @click="page++; load()">Sonraki</button>
        </div>
    </div>
</div>
