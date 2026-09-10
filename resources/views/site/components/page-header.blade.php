@props(['title', 'subtitle' => null, 'image' => null, 'breadcrumbs' => []])
@push('head')<script type="application/ld+json">{!! breadcrumb_schema($breadcrumbs) !!}</script>@endpush
<section class="relative overflow-hidden bg-primary-dark text-white">
    @if(setting('hero_image'))<img src="{{ upload_url(setting('hero_image')) }}" alt="" class="absolute inset-0 h-full w-full object-cover object-[right_center]">@endif
    <div class="page-overlay absolute inset-0"></div>
    <div class="container-x relative py-16">
        <nav class="mb-5 flex flex-wrap items-center gap-2 text-xs text-white/60" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-white">Ana Sayfa</a>
            @foreach($breadcrumbs as $crumb)
                <x-icon name="chevron-right" class="h-3 w-3 text-white/40" />
                @if(isset($crumb['url']))<a href="{{ $crumb['url'] }}" class="hover:text-white">{{ $crumb['label'] }}</a>@else<span class="text-accent">{{ $crumb['label'] }}</span>@endif
            @endforeach
        </nav>
        <div class="grid items-center gap-10 lg:grid-cols-3">
            <div class="lg:col-span-2">
                <h1 class="font-display text-4xl tracking-tight text-white md:text-5xl">{!! emphasize($title) !!}</h1>
                @if($subtitle)<p class="mt-4 max-w-2xl text-lg text-white/80">{{ $subtitle }}</p>@endif
            </div>
            @if($image)<img src="{{ upload_url($image) }}" alt="" class="hidden aspect-[4/3] w-full rounded-lg object-cover shadow-xl lg:block">@endif
        </div>
    </div>
</section>
