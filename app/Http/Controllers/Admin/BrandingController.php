<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlatformSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BrandingController extends Controller
{
    public function edit(): View
    {
        $settings = PlatformSetting::first() ?? new PlatformSetting();
        return view('admin.branding.edit', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
            'admin_primary_color' => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'admin_secondary_color' => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'form_primary_color' => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'form_secondary_color' => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'form_background_color' => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'form_instructions' => 'nullable|string|max:5000',
            'form_example_image' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:3072',
        ]);

        $settings = PlatformSetting::first();

        $data = [
            'admin_primary_color' => $validated['admin_primary_color'] ?? '#0d6efd',
            'admin_secondary_color' => $validated['admin_secondary_color'] ?? '#6c757d',
            'form_primary_color' => $validated['form_primary_color'] ?? '#008037',
            'form_secondary_color' => $validated['form_secondary_color'] ?? '#0065B3',
            'form_background_color' => $validated['form_background_color'] ?? '#f7f9fa',
            'form_instructions' => $validated['form_instructions'],
        ];

        if ($request->hasFile('logo')) {
            if ($settings && $settings->logo_path) {
                Storage::disk('public')->delete($settings->logo_path);
            }
            $data['logo_path'] = $request->file('logo')->store('branding', 'public');
        }

        if ($request->hasFile('form_example_image')) {
            if ($settings && $settings->form_example_image) {
                Storage::disk('public')->delete($settings->form_example_image);
            }
            $data['form_example_image'] = $request->file('form_example_image')->store('branding', 'public');
        }

        if ($settings) {
            $settings->update($data);
        } else {
            PlatformSetting::create($data);
        }

        return redirect()->route('admin.branding.edit')->with('success', 'Configuración de marca guardada exitosamente.');
    }
}
