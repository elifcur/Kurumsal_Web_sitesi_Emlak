@extends('layouts.admin')
@section('title', $faq->exists ? 'Soru Düzenle' : 'Yeni Soru')
@section('content')
    <x-admin.page-header :title="$faq->exists ? 'Soru Düzenle' : 'Yeni Soru'" />

    <form method="post" action="{{ $faq->exists ? route('admin.faqs.update', $faq) : route('admin.faqs.store') }}">
        @csrf
        @if($faq->exists)@method('PUT')@endif
        <div class="grid gap-6 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
                <div class="card"><div class="card-body space-y-4">
                    <x-admin.input name="question" label="Soru" :value="$faq->question" required maxlength="500" />
                    <x-admin.textarea name="answer" label="Cevap" :value="$faq->answer" required maxlength="5000" rows="6" />
                </div></div>
            </div>
            <div class="space-y-6">
                <div class="card"><div class="card-body space-y-4">
                    <x-admin.checkbox name="is_active" label="Aktif" :checked="$faq->is_active" />
                    <x-admin.input name="group" label="Grup" :value="$faq->group" maxlength="100" help="İsteğe bağlı; soruları gruplamak için (örn. Ödeme, Teslimat)." />
                    <x-admin.input name="sort_order" label="Sıra" type="number" min="0" :value="$faq->sort_order" />
                </div></div>
                <x-admin.form-actions :back="route('admin.faqs.index')" />
            </div>
        </div>
    </form>
@endsection
