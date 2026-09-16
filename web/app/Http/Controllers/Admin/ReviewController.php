<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(): View
    {
        $reviews = Review::with('product')->latest()->paginate(20);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function create(): View
    {
        $products = Product::active()->orderBy('title')->get();

        return view('admin.reviews.create', compact('products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => 'required|max:255',
            'customer_avatar' => 'nullable|image|max:1024',
            'product_id' => 'nullable|exists:products,id',
            'rating' => 'required|integer|between:1,5',
            'review_text' => 'required|string',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        DB::transaction(function () use ($request, &$validated) {
            if ($request->hasFile('customer_avatar')) {
                $file = $request->file('customer_avatar');
                $filename = time() . '_avatar.' . $file->getClientOriginalExtension();
                $file->storeAs('reviews', $filename, 'public');
                $validated['customer_avatar'] = $filename;
            } else {
                $validated['customer_avatar'] = null;
            }

            Review::create($validated);
        });

        return redirect()->route('admin.reviews.index')->with('success', 'Review created successfully.');
    }

    public function edit(Review $review): View
    {
        $products = Product::active()->orderBy('title')->get();

        return view('admin.reviews.edit', compact('review', 'products'));
    }

    public function update(Request $request, Review $review): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => 'required|max:255',
            'customer_avatar' => 'nullable|image|max:1024',
            'product_id' => 'nullable|exists:products,id',
            'rating' => 'required|integer|between:1,5',
            'review_text' => 'required|string',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        DB::transaction(function () use ($request, $review, &$validated) {
            if ($request->hasFile('customer_avatar')) {
                if ($review->customer_avatar) {
                    Storage::disk('public')->delete('reviews/' . $review->customer_avatar);
                }

                $file = $request->file('customer_avatar');
                $filename = time() . '_avatar.' . $file->getClientOriginalExtension();
                $file->storeAs('reviews', $filename, 'public');
                $validated['customer_avatar'] = $filename;
            } else {
                unset($validated['customer_avatar']);
            }

            $review->update($validated);
        });

        return redirect()->route('admin.reviews.index')->with('success', 'Review updated successfully.');
    }

    public function destroy(Review $review): RedirectResponse
    {
        DB::transaction(function () use ($review) {
            if ($review->customer_avatar) {
                Storage::disk('public')->delete('reviews/' . $review->customer_avatar);
            }

            $review->delete();
        });

        return redirect()->route('admin.reviews.index')->with('success', 'Review deleted successfully.');
    }

    public function toggle(Review $review): JsonResponse
    {
        $review->update(['is_active' => !$review->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $review->is_active,
        ]);
    }

    public function feature(Review $review): JsonResponse
    {
        $review->update(['is_featured' => !$review->is_featured]);

        return response()->json([
            'success' => true,
            'is_featured' => $review->is_featured,
        ]);
    }

    public function reorder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:reviews,id',
            'items.*.sort_order' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($validated) {
            foreach ($validated['items'] as $item) {
                Review::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
            }
        });

        return response()->json(['success' => true]);
    }
}
