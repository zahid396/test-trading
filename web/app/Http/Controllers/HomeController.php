<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\JobPosting;
use App\Models\Product;
use App\Models\Review;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Banner::active()->with('actionProduct')->orderBy('sort_order')->get();
        $products = Product::available()
            ->select(['id', 'title', 'subtitle', 'price', 'old_price', 'discount', 'image', 'status', 'is_active', 'sort_order'])
            ->orderBy('sort_order')
            ->get();
        $reviews = Review::active()->featured()->orderBy('sort_order')->limit(5)->get();
        $jobs = JobPosting::active()
            ->select(['id', 'title', 'slug', 'company_name', 'location', 'employment_type', 'salary_range', 'application_deadline', 'subtitle', 'image', 'sort_order'])
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->limit(4)
            ->get();

        return view('home', compact('banners', 'products', 'reviews', 'jobs'));
    }
}
