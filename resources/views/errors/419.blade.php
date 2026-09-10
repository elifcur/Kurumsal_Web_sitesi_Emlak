@extends('template::layouts.app')
@section('content')
    @section('seo')<x-seo title="Oturum Süresi Doldu" robots="noindex, nofollow" />@endsection
    <section class="section">
        <div class="container-x max-w-xl text-center">
            <p class="text-7xl font-extrabold text-primary">419</p>
            <h1 class="mt-4 text-2xl font-bold text-slate-900">Oturum Süresi Doldu</h1>
            <p class="mt-3 text-slate-600">Lütfen sayfayı yenileyip tekrar deneyin.</p>
            <a href="{{ route('home') }}" class="btn-primary mt-8"><x-icon name="arrow-left" class="h-4 w-4" /> Ana Sayfaya Dön</a>
        </div>
    </section>
@endsection
