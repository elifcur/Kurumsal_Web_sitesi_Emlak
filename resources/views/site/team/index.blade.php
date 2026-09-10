@extends('template::layouts.app')
@section('seo')<x-seo :title="setting('team_title', 'Ekibimiz')" :description="setting('team_intro')" />@endsection
@section('content')
    <x-page-header :title="setting('team_title', 'Ekibimiz')" :subtitle="setting('team_intro')" :breadcrumbs="[['label' => setting('team_title', 'Ekibimiz')]]" />
    <section class="section">
        <div class="container-x">
            @if($members->isEmpty())
                <x-empty-state icon="users" title="Henüz ekip üyesi eklenmedi" />
            @else
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">@foreach($members as $member)<x-team-card :member="$member" />@endforeach</div>
            @endif
        </div>
    </section>
@endsection
