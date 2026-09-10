<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Settings;
use App\Services\ThemeManager;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

class ThemeController extends Controller
{
    public function edit(ThemeManager $themes): View
    {
        return view('admin.theme.edit', [
            'themes' => $themes->all(),
            'current' => $themes->key(),
            'fonts' => config('themes.fonts'),
            'currentFont' => (string) setting('font', ''),
            'primaryColor' => $themes->primaryColor(),
        ]);
    }

    public function update(Request $request, Settings $settings, ThemeManager $themes): RedirectResponse
    {
        $data = $request->validate([
            'theme' => ['required', Rule::in(array_keys($themes->all()))],
            'font' => ['nullable', Rule::in(array_keys(config('themes.fonts')))],
            'primary_color' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'use_theme_color' => ['nullable', 'boolean'],
        ], [], ['theme' => 'Tema', 'font' => 'Yazı tipi', 'primary_color' => 'Ana renk']);

        $primary = $request->boolean('use_theme_color')
            ? ($themes->config($data['theme'])['primary_color'] ?? null)
            : ($data['primary_color'] ?? null);

        $settings->setMany([
            'theme' => $data['theme'],
            'font' => $data['font'] ?? null,
            'primary_color' => $primary,
        ], 'theme');

        $themes->forget();
        Cache::flush();

        return redirect()->route('admin.theme.edit')->with('success', 'Tema ayarları kaydedildi.');
    }
}
