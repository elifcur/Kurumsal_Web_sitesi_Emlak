<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TestimonialRequest;
use App\Models\Testimonial;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index(Request $request): View
    {
        $testimonials = Testimonial::query()
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%'.$request->string('q')->limit(100).'%'))
            ->ordered()
            ->paginate(config('cms.pagination.admin'))
            ->withQueryString();

        return view('admin.testimonials.index', ['testimonials' => $testimonials]);
    }

    public function create(): View
    {
        return view('admin.testimonials.form', ['testimonial' => new Testimonial(['is_active' => true, 'rating' => 5, 'sort_order' => 0])]);
    }

    public function store(TestimonialRequest $request): RedirectResponse
    {
        Testimonial::query()->create($request->payload());

        return redirect()->route('admin.testimonials.index')->with('success', 'Referans oluşturuldu.');
    }

    public function edit(Testimonial $testimonial): View
    {
        return view('admin.testimonials.form', ['testimonial' => $testimonial]);
    }

    public function update(TestimonialRequest $request, Testimonial $testimonial): RedirectResponse
    {
        $testimonial->update($request->payload());

        return redirect()->route('admin.testimonials.index')->with('success', 'Referans güncellendi.');
    }

    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->delete();

        return redirect()->route('admin.testimonials.index')->with('success', 'Referans silindi.');
    }
}
