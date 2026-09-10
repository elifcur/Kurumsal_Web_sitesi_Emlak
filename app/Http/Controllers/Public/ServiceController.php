<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Contracts\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        return view('template::services.index', [
            'services' => Service::query()->active()->ordered()->paginate(config('cms.pagination.public')),
        ]);
    }

    public function show(string $slug): View
    {
        $service = Service::query()->active()->where('slug', $slug)->firstOrFail();

        return view('template::services.show', [
            'service' => $service,
            'others' => Service::query()->active()->ordered()->whereKeyNot($service->id)->limit(6)->get(['title', 'slug', 'icon']),
        ]);
    }
}
