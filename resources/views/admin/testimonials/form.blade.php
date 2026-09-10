@extends('layouts.admin')
@section('title', $testimonial->exists ? 'Yorum Düzenle' : 'Yeni Yorum')
@section('content')
    <x-admin.page-header :title="$testimonial->exists ? 'Yorum Düzenle' : 'Yeni Yorum'" />

    <form method="post" action="{{ $testimonial->exists ? route('admin.testimonials.update', $testimonial) : route('admin.testimonials.store') }}">
        @csrf
        @if($testimonial->exists)@method('PUT')@endif
        <div class="grid gap-6 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
                <div class="card"><div class="card-body space-y-4">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <x-admin.input name="name" label="Ad Soyad" :value="$testimonial->name" required maxlength="150" />
                        <x-admin.input name="title" label="Firma / Unvan" :value="$testimonial->title" maxlength="150" help="Örn. ABC Ltd. Genel Müdürü" />
                    </div>
                    <x-admin.textarea name="content" label="Yorum" :value="$testimonial->content" required maxlength="2000" rows="5" />
                    <x-admin.select name="rating" label="Puan" :options="[5 => '5 – Mükemmel', 4 => '4 – Çok iyi', 3 => '3 – İyi', 2 => '2 – Orta', 1 => '1 – Zayıf']" :value="$testimonial->rating" required />
                </div></div>
            </div>
            <div class="space-y-6">
                <div class="card"><div class="card-body space-y-4">
                    <x-admin.checkbox name="is_active" label="Aktif" :checked="$testimonial->is_active" />
                    <x-admin.input name="sort_order" label="Sıra" type="number" min="0" :value="$testimonial->sort_order" />
                    <x-admin.image name="image" label="Fotoğraf" :value="$testimonial->image" />
                </div></div>
                <x-admin.form-actions :back="route('admin.testimonials.index')" />
            </div>
        </div>
    </form>
@endsection
