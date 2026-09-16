<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $activeProducts = Product::active()->count();

        $totalOrders = Order::count();
        $pendingOrders = Order::pending()->count();
        $verifiedOrders = Order::verified()->count();
        $deliveredOrders = Order::delivered()->count();
        $rejectedOrders = Order::rejected()->count();

        $totalRevenue = Order::where('status', 'delivered')->sum('amount');

        $totalReviews = Review::count();

        $recentOrders = Order::with('product')->latest()->take(5)->get();
        $recentReviews = Review::with('product')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'activeProducts',
            'totalOrders',
            'pendingOrders',
            'verifiedOrders',
            'deliveredOrders',
            'rejectedOrders',
            'totalRevenue',
            'totalReviews',
            'recentOrders',
            'recentReviews',
        ));
    }
}
