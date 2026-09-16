<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BannerController extends Controller
{
    public function index(): View
    {
        $banners = Banner::with('actionProduct')->orderBy('sort_order')->get();

        return view('admin.banners.index', compact('banners'));
    }

    public function create(): View
    {
        $products = Product::active()->orderBy('title')->get();

        return view('admin.banners.create', compact('products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'nullable|max:255',
            'subtitle' => 'nullable|max:255',
            'button_text' => 'nullable|max:100',
            'action_type' => 'required|in:product,product_page,external_url,none',
            'action_product_id' => 'nullable|required_if:action_type,product|exists:products,id',
            'action_url' => 'nullable|required_if:action_type,external_url',
            'image' => 'nullable|image|max:4096',
            'media_type' => 'required|in:image,youtube',
            'video_url' => 'nullable|required_if:media_type,youtube|string|max:500',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        DB::transaction(function () use ($request, &$validated) {
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . '_banner.' . $file->getClientOriginalExtension();
                $file->storeAs('banners', $filename, 'public');
                $validated['image'] = $filename;
            } else {
                $validated['image'] = null;
            }

            Banner::create($validated);
        });

        return redirect()->route('admin.banners.index')->with('success', 'Banner created successfully.');
    }

    public function edit(Banner $banner): View
    {
        $products = Product::active()->orderBy('title')->get();

        return view('admin.banners.edit', compact('banner', 'products'));
    }

    public function update(Request $request, Banner $banner): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'nullable|max:255',
            'subtitle' => 'nullable|max:255',
            'button_text' => 'nullable|max:100',
            'action_type' => 'required|in:product,product_page,external_url,none',
            'action_product_id' => 'nullable|required_if:action_type,product|exists:products,id',
            'action_url' => 'nullable|required_if:action_type,external_url',
            'image' => 'nullable|image|max:4096',
            'media_type' => 'required|in:image,youtube',
            'video_url' => 'nullable|required_if:media_type,youtube|string|max:500',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        DB::transaction(function () use ($request, $banner, &$validated) {
            if ($request->hasFile('image')) {
                if ($banner->image) {
                    Storage::disk('public')->delete('banners/' . $banner->image);
                }

                $file = $request->file('image');
                $filename = time() . '_banner.' . $file->getClientOriginalExtension();
                $file->storeAs('banners', $filename, 'public');
                $validated['image'] = $filename;
            } else {
                unset($validated['image']);
            }

            $banner->update($validated);
        });

        return redirect()->route('admin.banners.index')->with('success', 'Banner updated successfully.');
    }

    public function destroy(Banner $banner): RedirectResponse
    {
        DB::transaction(function () use ($banner) {
            if ($banner->image) {
                Storage::disk('public')->delete('banners/' . $banner->image);
            }

            $banner->delete();
        });

        return redirect()->route('admin.banners.index')->with('success', 'Banner deleted successfully.');
    }

    public function toggle(Banner $banner): JsonResponse
    {
        $banner->update(['is_active' => !$banner->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $banner->is_active,
        ]);
    }

    public function reorder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:banners,id',
            'items.*.sort_order' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($validated) {
            foreach ($validated['items'] as $item) {
                Banner::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
            }
        });

        return response()->json(['success' => true]);
    }
}
