<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach($entries as $entry)
    <url>
        <loc>{{ $entry['url'] }}</loc>
@if($entry['updated'])
        <lastmod>{{ \Illuminate\Support\Carbon::parse($entry['updated'])->toAtomString() }}</lastmod>
@endif
        <priority>{{ $entry['priority'] }}</priority>
    </url>
@endforeach
</urlset>
