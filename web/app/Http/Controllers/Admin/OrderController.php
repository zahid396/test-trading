<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderNote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $query = Order::with('product');

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $orders = $query->latest()->paginate(20)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $order->load(['product', 'notes']);

        return view('admin.orders.show', compact('order'));
    }

    public function verify(Order $order): RedirectResponse
    {
        $order->update([
            'status' => 'verified',
            'confirmed_at' => now(),
        ]);

        return redirect()->route('admin.orders.show', $order)->with('success', 'Order verified successfully.');
    }

    public function reject(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        $order->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['reason'] ?? null,
        ]);

        return redirect()->route('admin.orders.show', $order)->with('success', 'Order rejected.');
    }

    public function deliver(Order $order): RedirectResponse
    {
        $order->update([
            'status' => 'delivered',
            'delivered_at' => now(),
        ]);

        return redirect()->route('admin.orders.show', $order)->with('success', 'Order marked as delivered.');
    }

    public function addNote(Request $request, Order $order): JsonResponse
    {
        $validated = $request->validate([
            'note' => 'required|string',
        ]);

        $note = $order->notes()->create([
            'note' => $validated['note'],
            'created_by' => auth()->guard('admin')->user()->name ?? 'Admin',
        ]);

        return response()->json([
            'success' => true,
            'note' => $note,
        ]);
    }

    public function updateNotes(Request $request, Order $order): JsonResponse
    {
        $validated = $request->validate([
            'admin_notes' => 'nullable|string',
        ]);

        $order->update(['admin_notes' => $validated['admin_notes']]);

        return response()->json(['success' => true]);
    }
}
