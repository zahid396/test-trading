<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocialLink;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SocialLinkController extends Controller
{
    public function index(): View
    {
        $socialLinks = SocialLink::orderBy('sort_order')->get();

        return view('admin.social-links.index', compact('socialLinks'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'platform' => 'required|max:50',
            'label' => 'nullable|max:100',
            'url' => 'nullable|url',
            'icon' => 'nullable|max:100',
            'is_active' => 'boolean',
        ]);

        SocialLink::create($validated);

        Cache::forget('shop.social_links');

        return redirect()->route('admin.social-links.index')->with('success', 'Social link created successfully.');
    }

    public function update(Request $request, SocialLink $socialLink): RedirectResponse
    {
        $validated = $request->validate([
            'platform' => 'required|max:50',
            'label' => 'nullable|max:100',
            'url' => 'nullable|url',
            'icon' => 'nullable|max:100',
            'is_active' => 'boolean',
        ]);

        $socialLink->update($validated);

        Cache::forget('shop.social_links');

        return redirect()->route('admin.social-links.index')->with('success', 'Social link updated successfully.');
    }

    public function destroy(SocialLink $socialLink): RedirectResponse
    {
        $socialLink->delete();

        Cache::forget('shop.social_links');

        return redirect()->route('admin.social-links.index')->with('success', 'Social link deleted successfully.');
    }

    public function toggle(SocialLink $socialLink): JsonResponse
    {
        $socialLink->update(['is_active' => !$socialLink->is_active]);

        Cache::forget('shop.social_links');

        return response()->json([
            'success' => true,
            'is_active' => $socialLink->is_active,
        ]);
    }

    public function reorder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:social_links,id',
            'items.*.sort_order' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($validated) {
            foreach ($validated['items'] as $item) {
                SocialLink::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
            }
        });

        Cache::forget('shop.social_links');

        return response()->json(['success' => true]);
    }
}
