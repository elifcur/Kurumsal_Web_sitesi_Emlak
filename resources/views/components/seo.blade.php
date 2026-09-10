@props([
    'title' => null,
    'description' => null,
    'canonical' => null,
    'image' => null,
    'type' => 'website',
    'robots' => 'index, follow',
])
@php
    $siteName = setting('site_name', config('app.name'));
    $fullTitle = $title ? "{$title} | {$siteName}" : setting('meta_title', $siteName);
    $desc = \Illuminate\Support\Str::limit(strip_tags($description ?: setting('meta_description', '')), 300, '');
    $ogImage = upload_url($image ?: setting('og_image'));
    $canonicalUrl = $canonical ?: url()->current();
@endphp
<title>{{ $fullTitle }}</title>
<meta name="description" content="{{ $desc }}">
<meta name="robots" content="{{ $robots }}">
<link rel="canonical" href="{{ $canonicalUrl }}">
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:type" content="{{ $type }}">
<meta property="og:title" content="{{ $fullTitle }}">
<meta property="og:description" content="{{ $desc }}">
<meta property="og:url" content="{{ $canonicalUrl }}">
@if($ogImage)
<meta property="og:image" content="{{ $ogImage }}">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:image" content="{{ $ogImage }}">
@else
<meta name="twitter:card" content="summary">
@endif
<meta name="twitter:title" content="{{ $fullTitle }}">
<meta name="twitter:description" content="{{ $desc }}">
@if(setting('google_site_verification'))
<meta name="google-site-verification" content="{{ setting('google_site_verification') }}">
@endif
