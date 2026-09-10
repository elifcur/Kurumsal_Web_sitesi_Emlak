@props(['testimonial'])
<figure class="card flex h-full flex-col p-6">
    <div class="flex text-accent" aria-label="{{ $testimonial->rating }} yıldız">@for($i = 0; $i < $testimonial->rating; $i++)<x-icon name="star" class="h-4 w-4 fill-current" />@endfor</div>
    <blockquote class="mt-4 flex-1 font-display text-lg leading-8 text-slate-700 italic">“{{ $testimonial->content }}”</blockquote>
    <figcaption class="mt-5 flex items-center gap-3">
        <span class="flex h-10 w-10 shrink-0 items-center justify-center overflow-hidden rounded-full bg-primary-light text-sm font-bold text-primary">@if($testimonial->image)<img src="{{ upload_url($testimonial->image) }}" alt="" class="h-full w-full object-cover">@else{{ mb_substr($testimonial->name, 0, 1) }}@endif</span>
        <span><span class="block text-sm font-semibold text-ink">{{ $testimonial->name }}</span>@if($testimonial->title)<span class="block text-xs text-slate-500">{{ $testimonial->title }}</span>@endif</span>
    </figcaption>
</figure>
