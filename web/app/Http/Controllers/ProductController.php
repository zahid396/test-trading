<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::available();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('subtitle', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $category = Category::where('slug', $request->input('category'))
                ->orWhere('id', $request->input('category'))
                ->first();

            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        $categories = Category::active()->orderBy('sort_order')->get();
        $products = $query
            ->select(['id', 'title', 'subtitle', 'price', 'old_price', 'discount', 'image', 'status', 'is_active', 'category_id', 'sort_order'])
            ->orderBy('sort_order')
            ->paginate(12);

        return view('products.index', compact('products', 'categories'));
    }

    public function show(string $slug)
    {
        $product = Product::active()
            ->where('slug', $slug)
            ->with(['reviews' => fn ($q) => $q->active()])
            ->firstOrFail();

        return view('products.show', compact('product'));
    }

    public function details(int $id)
    {
        $product = Product::active()->with(['reviews' => fn ($q) => $q->active()])->find($id);

        if (!$product) {
            return response()->json(['error' => 'Product not found.'], 404);
        }

        $product->setAttribute('effective_price', $product->effective_price);
        $product->setAttribute('discount_percentage', $product->discount_percentage);

        return response()->json([
            'product' => $product,
            'reviews' => $product->reviews,
        ]);
    }
}
