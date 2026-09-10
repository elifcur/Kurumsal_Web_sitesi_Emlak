@extends('template::layouts.app')
@section('seo')<x-seo :title="setting('team_home_title', 'Ekibimiz')" description="Uzman gayrimenkul danışmanlarımızla tanışın." />@endsection
@section('content')
    <x-page-header :title="setting('team_home_title', 'Ekibimiz')" subtitle="Bölgesini tanıyan, deneyimli danışmanlarımız size en doğru mülkü bulmak için çalışır." :breadcrumbs="[['label' => 'Ekibimiz']]" />
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
