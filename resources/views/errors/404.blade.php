@extends('template::layouts.app')
@section('content')
    @section('seo')<x-seo title="Sayfa Bulunamadı" robots="noindex, nofollow" />@endsection
    <section class="section">
        <div class="container-x max-w-xl text-center">
            <p class="text-7xl font-extrabold text-primary">404</p>
            <h1 class="mt-4 text-2xl font-bold text-slate-900">Aradığınız sayfa bulunamadı</h1>
            <p class="mt-3 text-slate-600">Sayfa taşınmış veya kaldırılmış olabilir.</p>
            <a href="{{ route('home') }}" class="btn-primary mt-8"><x-icon name="arrow-left" class="h-4 w-4" /> Ana Sayfaya Dön</a>
        </div>
    </section>
@endsection
