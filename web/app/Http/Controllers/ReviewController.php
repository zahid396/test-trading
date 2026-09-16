<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:100'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'review_text' => ['required', 'string', 'max:1000'],
        ]);

        $product->reviews()->create([
            'customer_name' => $validated['customer_name'],
            'rating' => $validated['rating'],
            'review_text' => $validated['review_text'],
            'is_active' => false,
            'is_featured' => false,
            'sort_order' => 0,
        ]);

        return back()->with(
            'review_success',
            'Thank you! Your review has been submitted and is awaiting admin approval.'
        );
    }
}