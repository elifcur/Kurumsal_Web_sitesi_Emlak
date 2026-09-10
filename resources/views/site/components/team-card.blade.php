@props(['member'])
<a href="{{ $member->url() }}" class="card group overflow-hidden">
    <div class="aspect-[4/3] overflow-hidden bg-sand">
        @if($member->image)<img src="{{ upload_url($member->image) }}" alt="{{ $member->name }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-105" loading="lazy">@else<div class="flex h-full items-center justify-center text-primary/30"><x-icon name="user" class="h-20 w-20" /></div>@endif
    </div>
    <div class="p-5">
        <h3 class="font-semibold text-ink group-hover:text-primary">{{ $member->name }}</h3>
        @if($member->title)<p class="mt-1 text-sm text-slate-500">{{ $member->title }}</p>@endif
        @if($first = $member->attributes_list()[0] ?? null)<p class="mt-3 flex items-center gap-1 text-xs text-slate-500"><x-icon name="check-circle" class="h-3.5 w-3.5 text-accent" /> {{ $first['value'] }}</p>@endif
    </div>
</a>
