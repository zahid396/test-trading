<?php

namespace App\Http\Controllers;

use App\Exceptions\CheckoutSessionUsedException;
use App\Models\CheckoutSession;
use App\Models\Order;
use App\Models\PaymentSetting;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function show(int $productId)
    {
        $product = Product::available()->find($productId);

        if (!$product) {
            return redirect()->route('products.index')->with('error', 'Product not found or unavailable.');
        }

        $sessionId = Str::uuid()->toString();

        $session = CheckoutSession::create([
            'session_id' => $sessionId,
            'product_id' => $product->id,
            'expires_at' => now()->addMinutes(30),
        ]);

        $session->session_id = $sessionId;
        session(['checkout_session_id' => $sessionId]);

        return view('checkout.show', compact('product', 'session'));
    }

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'session_id' => ['required', 'exists:checkout_sessions,session_id'],
            'product_id' => ['required'],
            'customer_email' => ['required', 'email'],
            'payment_method' => ['required', 'in:bkash,nagad'],
            'sender_number' => ['required', 'regex:/^01[3-9]\d{8}$/'],
            'transaction_id' => ['required', 'max:50'],
        ], [
            'sender_number.regex' => 'Please provide a valid Bangladeshi mobile number (e.g. 01XXXXXXXXX).',
        ]);

        $session = CheckoutSession::where('session_id', $validated['session_id'])->first();

        if (!$session) {
            return back()->with('error', 'Invalid checkout session.');
        }

        if ($session->isExpired()) {
            return back()->with('error', 'This checkout session has expired. Please start a new checkout.');
        }

        if ($session->is_used) {
            return back()->with('error', 'This checkout session has already been used.');
        }

        $product = Product::available()->find($validated['product_id']);

        if (!$product) {
            return back()->with('error', 'Product not found or unavailable.');
        }

        $paymentMethod = PaymentSetting::where('method', $validated['payment_method'])
            ->where('is_active', true)
            ->first();

        if (!$paymentMethod) {
            return back()->with('error', 'Selected payment method is currently unavailable. Please choose another.');
        }

        // NEVER trust the price from the frontend; always use the DB value.
        $amount = $product->effective_price;

        try {
            $orderId = DB::transaction(function () use ($validated, $product, $amount, $session) {
                // Atomically reserve the session so two concurrent submissions
                // for the same checkout can never both succeed.
                $reserved = CheckoutSession::where('id', $session->id)
                    ->where('is_used', false)
                    ->update(['is_used' => true]);

                if ($reserved === 0) {
                    throw new CheckoutSessionUsedException;
                }

                // Order ID generation is inside the transaction and takes a
                // row lock so concurrent submissions get unique sequences.
                $orderId = $this->generateOrderId();

                Order::create([
                    'order_id' => $orderId,
                    'product_id' => $product->id,
                    'customer_email' => $validated['customer_email'],
                    'payment_method' => $validated['payment_method'],
                    'sender_number' => $validated['sender_number'],
                    'transaction_id' => $validated['transaction_id'],
                    'amount' => $amount,
                ]);

                return $orderId;
            });

            session()->forget('checkout_session_id');

            return view('checkout.success', ['orderId' => $orderId]);
        } catch (CheckoutSessionUsedException $e) {
            return back()->with('error', 'This checkout session has already been used.');
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong while processing your order. Please try again.');
        }
    }

    protected function generateOrderId(): string
    {
        $date = now()->format('Ymd');

        $lastOrder = Order::where('order_id', 'like', "ORD-{$date}-%")
            ->orderByDesc('order_id')
            ->lockForUpdate()
            ->first();

        $sequence = 1;
        if ($lastOrder) {
            $lastNum = (int) substr($lastOrder->order_id, -4);
            $sequence = $lastNum + 1;
        }

        return sprintf('ORD-%s-%04d', $date, $sequence);
    }
}
