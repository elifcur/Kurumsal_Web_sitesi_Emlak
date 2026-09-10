@props(['service'])
<a href="{{ $service->url() }}" class="card group flex flex-col overflow-hidden">
    @if($service->image)
        <div class="aspect-[16/10] overflow-hidden bg-slate-100"><img src="{{ upload_url($service->image) }}" alt="{{ $service->title }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-105" loading="lazy"></div>
    @endif
    <div class="flex flex-1 flex-col p-6">
        @if(! $service->image)<span class="mb-5 flex h-12 w-12 items-center justify-center rounded-md bg-primary-light text-primary transition group-hover:bg-primary group-hover:text-primary-contrast"><x-icon :name="$service->icon ?: 'briefcase'" class="h-6 w-6" /></span>@endif
        <h3 class="text-lg font-semibold text-ink group-hover:text-primary">{{ $service->title }}</h3>
        @if($service->excerpt)<p class="mt-2 line-clamp-2 text-sm leading-6 text-slate-600">{{ $service->excerpt }}</p>@endif
        <span class="mt-5 inline-flex items-center gap-1 text-sm font-medium text-ink">Detaylı Bilgi <x-icon name="arrow-right" class="h-4 w-4 text-accent transition group-hover:translate-x-1" /></span>
    </div>
</a>
