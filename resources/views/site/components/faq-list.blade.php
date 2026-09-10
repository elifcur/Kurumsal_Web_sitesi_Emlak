@props(['faqs'])
<div x-data="accordion()" class="divide-y divide-slate-200 rounded-lg border border-slate-200 bg-white">
    @foreach($faqs as $faq)
        <div>
            <button type="button" @click="toggle({{ $faq->id }})" class="flex w-full items-center justify-between gap-4 px-6 py-5 text-left font-semibold text-ink" :aria-expanded="isOpen({{ $faq->id }})">
                <span>{{ $faq->question }}</span>
                <x-icon name="chevron-down" class="h-5 w-5 shrink-0 text-accent transition" ::class="isOpen({{ $faq->id }}) && 'rotate-180'" />
            </button>
            <div x-show="isOpen({{ $faq->id }})" x-collapse x-cloak class="px-6 pb-5 text-sm leading-7 text-slate-600">{{ $faq->answer }}</div>
        </div>
    @endforeach
</div>
