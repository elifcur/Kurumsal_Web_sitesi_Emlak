<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PresetApplier;
use App\Services\ThemeManager;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class PresetController extends Controller
{
    public function index(PresetApplier $presets, ThemeManager $themes): View
    {
        return view('admin.presets.index', [
            'presets' => $presets->all(),
            'themes' => $themes->all(),
            'active' => (string) setting('active_preset', ''),
        ]);
    }

    public function apply(string $preset, PresetApplier $presets): RedirectResponse
    {
        abort_unless($presets->exists($preset), 404);

        $presets->apply($preset);

        return redirect()->route('admin.presets.index')->with('success', '"'.$presets->all()[$preset]['name'].'" ön ayarı uygulandı. Tema, renk, menü etiketleri ve modüller güncellendi.');
    }
}
