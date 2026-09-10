<div>
    <h3 class="text-xs font-semibold tracking-[0.2em] text-white uppercase">{{ setting('listings_label', 'İlanlar') }}</h3>
    <ul class="mt-5 space-y-2.5 text-sm">
        <li><a href="{{ route('listings.index', ['tur' => 'sale']) }}" class="transition hover:text-accent">Satılık İlanlar</a></li>
        <li><a href="{{ route('listings.index', ['tur' => 'rent']) }}" class="transition hover:text-accent">Kiralık İlanlar</a></li>
        @foreach(['apartment' => 'Daire', 'villa' => 'Villa', 'office' => 'Ofis', 'land' => 'Arsa'] as $key => $label)
            <li><a href="{{ route('listings.index', ['tip' => $key]) }}" class="transition hover:text-accent">{{ $label }}</a></li>
        @endforeach
    </ul>
</div>
