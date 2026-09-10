@props(['post'])
<article class="card group flex flex-col overflow-hidden">
    <a href="{{ $post->url() }}" class="block aspect-[16/10] w-full overflow-hidden bg-slate-100">
        @if($post->image)<img src="{{ upload_url($post->image) }}" alt="{{ $post->title }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-105" loading="lazy">@else<div class="flex h-full w-full items-center justify-center bg-sand text-primary/30"><x-icon name="newspaper" class="h-12 w-12" /></div>@endif
    </a>
    <div class="flex flex-1 flex-col p-5">
        <div class="flex items-center gap-3 text-xs text-slate-500">
            @if($post->published_at)<time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->translatedFormat('d F Y') }}</time>@endif
            @if($post->category)<span class="rounded-md bg-primary-light px-2 py-0.5 font-medium text-primary">{{ $post->category->name }}</span>@endif
        </div>
        <h3 class="mt-2 text-lg font-semibold text-ink"><a href="{{ $post->url() }}" class="hover:text-primary">{{ $post->title }}</a></h3>
        @if($post->excerpt)<p class="mt-2 line-clamp-2 text-sm leading-6 text-slate-600">{{ $post->excerpt }}</p>@endif
        <a href="{{ $post->url() }}" class="mt-4 inline-flex items-center gap-1 text-sm font-medium text-ink">Devamını Oku <x-icon name="arrow-right" class="h-4 w-4 text-accent" /></a>
    </div>
</article>
