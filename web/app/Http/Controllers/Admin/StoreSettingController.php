<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StoreSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class StoreSettingController extends Controller
{
    public function index(): View
    {
        $stored = StoreSetting::pluck('value', 'key')->all();

        $settings = [
            'store_name' => $stored['store_name'] ?? '',
            'store_logo' => $stored['store_logo'] ?? '',
            'store_favicon' => $stored['store_favicon'] ?? '',
            'about_text' => $stored['about_text'] ?? '',
            'contact_email' => $stored['contact_email'] ?? '',
            'contact_phone' => $stored['contact_phone'] ?? '',
            'copyright_text' => $stored['copyright_text'] ?? '',
            'footer_text' => $stored['footer_text'] ?? '',
            'hero_badge' => $stored['hero_badge'] ?? '',
            'hero_title' => $stored['hero_title'] ?? '',
            'hero_title_highlight' => $stored['hero_title_highlight'] ?? '',
            'hero_subtitle' => $stored['hero_subtitle'] ?? '',
            'hero_cta1_text' => $stored['hero_cta1_text'] ?? '',
            'hero_cta1_url' => $stored['hero_cta1_url'] ?? '',
            'hero_cta2_text' => $stored['hero_cta2_text'] ?? '',
            'hero_cta2_url' => $stored['hero_cta2_url'] ?? '',
            'hero_stat1_value' => $stored['hero_stat1_value'] ?? '',
            'hero_stat1_label' => $stored['hero_stat1_label'] ?? '',
            'hero_stat2_value' => $stored['hero_stat2_value'] ?? '',
            'hero_stat2_label' => $stored['hero_stat2_label'] ?? '',
            'hero_stat3_value' => $stored['hero_stat3_value'] ?? '',
            'hero_stat3_label' => $stored['hero_stat3_label'] ?? '',
            'hero_image' => $stored['hero_image'] ?? '',
        ];

        return view('admin.store-settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'store_name' => 'required|max:255',
            'store_logo' => 'nullable|image|max:2048',
            'store_favicon' => 'nullable|image|max:1024',
            'about_text' => 'nullable',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|max:20',
            'copyright_text' => 'nullable|max:500',
            'footer_text' => 'nullable',
            'hero_badge' => 'nullable|max:255',
            'hero_title' => 'nullable|max:255',
            'hero_title_highlight' => 'nullable|max:255',
            'hero_subtitle' => 'nullable|max:1000',
            'hero_cta1_text' => 'nullable|max:100',
            'hero_cta1_url' => 'nullable|max:500',
            'hero_cta2_text' => 'nullable|max:100',
            'hero_cta2_url' => 'nullable|max:500',
            'hero_stat1_value' => 'nullable|max:100',
            'hero_stat1_label' => 'nullable|max:150',
            'hero_stat2_value' => 'nullable|max:100',
            'hero_stat2_label' => 'nullable|max:150',
            'hero_stat3_value' => 'nullable|max:100',
            'hero_stat3_label' => 'nullable|max:150',
            'hero_image' => 'nullable|image|max:4096',
        ]);

        if ($request->hasFile('store_logo')) {
            $oldLogo = StoreSetting::get('store_logo');
            if ($oldLogo) {
                Storage::disk('public')->delete('store/' . $oldLogo);
            }

            $file = $request->file('store_logo');
            $filename = time() . '_logo.' . $file->getClientOriginalExtension();
            $file->storeAs('store', $filename, 'public');
            $validated['store_logo'] = $filename;
        } else {
            unset($validated['store_logo']);
        }

        if ($request->hasFile('store_favicon')) {
            $oldFavicon = StoreSetting::get('store_favicon');
            if ($oldFavicon) {
                Storage::disk('public')->delete('store/' . $oldFavicon);
            }

            $file = $request->file('store_favicon');
            $filename = time() . '_favicon.' . $file->getClientOriginalExtension();
            $file->storeAs('store', $filename, 'public');
            $validated['store_favicon'] = $filename;
        } else {
            unset($validated['store_favicon']);
        }

        if ($request->hasFile('hero_image')) {
            $oldHeroImage = StoreSetting::get('hero_image');
            if ($oldHeroImage) {
                Storage::disk('public')->delete('store/' . $oldHeroImage);
            }

            $file = $request->file('hero_image');
            $filename = time() . '_hero.' . $file->getClientOriginalExtension();
            $file->storeAs('store', $filename, 'public');
            $validated['hero_image'] = $filename;
        } else {
            unset($validated['hero_image']);
        }

        foreach ($validated as $key => $value) {
            StoreSetting::set($key, $value);
        }

        Cache::forget('shop.store_settings');

        return redirect()->route('admin.store-settings.index')->with('success', 'Store settings updated successfully.');
    }
}
