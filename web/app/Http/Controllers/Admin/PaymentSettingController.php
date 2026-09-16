<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PaymentSettingController extends Controller
{
    public function index(): View
    {
        $bkash = PaymentSetting::where('method', 'bkash')->first();
        $nagad = PaymentSetting::where('method', 'nagad')->first();

        return view('admin.payment-settings.index', compact('bkash', 'nagad'));
    }

    public function update(Request $request, string $method): RedirectResponse
    {
        $validated = $request->validate([
            'number' => 'required|max:20',
            'account_type' => 'nullable|max:50',
            'instructions' => 'nullable',
            'qr_image' => 'nullable|image|max:2048',
            'logo' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        $setting = PaymentSetting::firstOrCreate(
            ['method' => $method],
            ['number' => $validated['number']]
        );

        if ($request->hasFile('qr_image')) {
            if ($setting->qr_image) {
                Storage::disk('public')->delete('payments/' . $setting->qr_image);
            }

            $file = $request->file('qr_image');
            $filename = time() . '_' . $method . '_qr.' . $file->getClientOriginalExtension();
            $file->storeAs('payments', $filename, 'public');
            $validated['qr_image'] = $filename;
        } else {
            unset($validated['qr_image']);
        }

        if ($request->hasFile('logo')) {
            if ($setting->logo) {
                Storage::disk('public')->delete('payments/' . $setting->logo);
            }

            $file = $request->file('logo');
            $filename = time() . '_' . $method . '_logo.' . $file->getClientOriginalExtension();
            $file->storeAs('payments', $filename, 'public');
            $validated['logo'] = $filename;
        } else {
            unset($validated['logo']);
        }

        $setting->update($validated);

        Cache::forget('shop.payment_methods');

        return redirect()->route('admin.payment-settings.index')->with('success', ucfirst($method) . ' settings updated successfully.');
    }

    public function toggle(string $method): JsonResponse
    {
        $setting = PaymentSetting::where('method', $method)->firstOrFail();

        $setting->update(['is_active' => !$setting->is_active]);

        Cache::forget('shop.payment_methods');

        return response()->json([
            'success' => true,
            'is_active' => $setting->is_active,
        ]);
    }
}
