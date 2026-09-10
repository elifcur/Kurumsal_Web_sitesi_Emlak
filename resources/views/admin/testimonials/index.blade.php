@extends('layouts.admin')
@section('title', 'Referanslar')
@section('content')
    <x-admin.page-header title="Müşteri Yorumları" subtitle="Sitede gösterilen referans ve müşteri görüşleri.">
        <x-slot:actions><a href="{{ route('admin.testimonials.create') }}" class="btn-primary"><x-icon name="plus" class="h-4 w-4" /> Yeni Yorum</a></x-slot:actions>
    </x-admin.page-header>

    <div class="card">
        <div class="card-header"><x-admin.search placeholder="İsim ara..." /></div>
        @if($testimonials->isEmpty())
            <x-admin.empty title="Yorum bulunamadı" icon="star" />
        @else
            <div class="overflow-x-auto"><table class="table">
                <thead><tr><th>Kişi</th><th>Yorum</th><th>Puan</th><th>Durum</th><th class="text-right">İşlem</th></tr></thead>
                <tbody>
                @foreach($testimonials as $testimonial)
                    <tr>
                        <td><a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="font-medium text-slate-900 hover:text-primary">{{ $testimonial->name }}</a><span class="block text-xs text-slate-500">{{ $testimonial->title }}</span></td>
                        <td class="max-w-md truncate text-slate-600">{{ \Illuminate\Support\Str::limit($testimonial->content, 90) }}</td>
                        <td class="text-amber-500">{{ str_repeat('★', $testimonial->rating) }}</td>
                        <td><x-admin.status-badge :active="$testimonial->is_active" /></td>
                        <td class="text-right whitespace-nowrap">
                            <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="btn-ghost btn-sm"><x-icon name="pencil" class="h-4 w-4" /></a>
                            <x-admin.delete-button :action="route('admin.testimonials.destroy', $testimonial)" />
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table></div>
            <div class="px-5 py-4">{{ $testimonials->links() }}</div>
        @endif
    </div>
@endsection
