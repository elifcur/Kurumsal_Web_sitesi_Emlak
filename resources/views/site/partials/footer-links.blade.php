@if($footerServices !== [])
<div>
    <h3 class="text-xs font-semibold tracking-[0.2em] text-white uppercase">{{ setting('services_label', 'Hizmetler') }}</h3>
    <ul class="mt-5 space-y-2.5 text-sm">
        @foreach($footerServices as $service)<li><a href="{{ route('services.show', $service['slug']) }}" class="transition hover:text-accent">{{ $service['title'] }}</a></li>@endforeach
    </ul>
</div>
@else
<div>
    <h3 class="text-xs font-semibold tracking-[0.2em] text-white uppercase">Kurumsal</h3>
    <ul class="mt-5 space-y-2.5 text-sm">
        @foreach($footerPages as $page)<li><a href="{{ route('page.show', $page['slug']) }}" class="transition hover:text-accent">{{ $page['title'] }}</a></li>@endforeach
    </ul>
</div>
@endif
