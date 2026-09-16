<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function track()
    {
        return view('order.track');
    }

    public function search(Request $request)
    {
        $validated = $request->validate([
            'order_id' => ['required'],
        ]);

        $orderId = trim((string) $validated['order_id']);
        $orderId = preg_replace('/\s+/', '', $orderId);
        $orderId = ltrim($orderId, '#' . "\t\n\r\0\x0B");

        $query = Order::query();

        if (preg_match('/^\d{4}$/', $orderId)) {
            $query->where('order_id', 'like', '%-' . $orderId);
        } else {
            $query->where('order_id', $orderId);
        }

        $order = $query->with('product')->first();

        if (!$order) {
            return back()->with('error', 'No order found with ID "' . $validated['order_id'] . '". Please check and try again.')
                ->withInput($request->only('order_id'));
        }

        return view('order.track', compact('order'));
    }
}
