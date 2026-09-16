@extends('layouts.app')
@section('head')
<style>
.success-page { padding: 3rem 0 4rem; }
.success-card { max-width: 560px; margin: 0 auto; background: var(--white); border-radius: var(--radius-xl); padding: 3rem 2.5rem; text-align: center; box-shadow: var(--shadow-md); border: 1px solid var(--gray-200); }
.success-icon { width: 72px; height: 72px; border-radius: 50%; background: rgba(22,199,132,.16); color: #16c784; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem; font-size: 2rem; animation: popIn .4s cubic-bezier(.175,.885,.32,1.275); box-shadow: 0 0 0 6px rgba(22,199,132,.08); }
@keyframes popIn { from { transform: scale(0); } to { transform: scale(1); } }
.success-card h1 { font-size: 1.5rem; font-weight: 800; color: var(--gray-900); margin-bottom: .5rem; }
.success-card .success-msg { color: var(--gray-500); font-size: .95rem; margin-bottom: 2rem; }
.order-id-box { display: inline-block; background: var(--primary-50); border: 2px dashed rgba(240,185,11,.5); border-radius: var(--radius); padding: .75rem 2rem; margin-bottom: 2rem; }
.order-id-box span { font-size: .78rem; color: var(--gray-500); display: block; text-transform: uppercase; letter-spacing: .5px; margin-bottom: .25rem; }
.order-id-box strong { font-size: 1.35rem; font-weight: 800; color: var(--primary); letter-spacing: 1px; }
.summary-details { text-align: left; background: var(--gray-50); border-radius: var(--radius); padding: 1.5rem; margin-bottom: 2rem; border: 1px solid var(--gray-100); }
.summary-details .detail-row { display: flex; justify-content: space-between; padding: .55rem 0; font-size: .9rem; border-bottom: 1px solid var(--gray-100); }
.summary-details .detail-row:last-child { border-bottom: none; }
.summary-details .detail-label { color: var(--gray-500); }
.summary-details .detail-value { font-weight: 600; color: var(--gray-800); text-align: right; max-width: 60%; word-break: break-word; }
.status-badge { display: inline-block; padding: .25rem .75rem; border-radius: 50px; font-size: .78rem; font-weight: 700; background: rgba(245,158,11,.16); color: #fbbf24; border: 1px solid rgba(245,158,11,.35); }
.btn-track-order { display: inline-flex; align-items: center; gap: .5rem; padding: .85rem 2rem; background: linear-gradient(135deg, #f0b90b, #e2a008); color: #0b0f1c; font-family: var(--font-display); font-weight: 700; font-size: 1rem; border: none; border-radius: var(--radius); cursor: pointer; transition: var(--transition); text-decoration: none; box-shadow: 0 6px 20px rgba(240,185,11,.25); }
.btn-track-order:hover { opacity: .94; transform: translateY(-2px); color: #0b0f1c; box-shadow: var(--shadow-lg); }
.success-note { margin-top: 1.5rem; font-size: .82rem; color: var(--gray-400); line-height: 1.6; }

@media (max-width: 480px) {
    .success-card { padding: 2rem 1.25rem; margin: 0 1rem; }
}
</style>
@endsection

@section('content')
<div class="success-page">
    <div class="container">
        <div class="success-card">
            <div class="success-icon">
                <svg width="36" height="36" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 8 10 18 6 14"/></svg>
            </div>
            <h1>Order Submitted Successfully!</h1>
            <p class="success-msg">Your order has been received. We'll verify your payment shortly.</p>

            <div class="order-id-box">
                <span>Your Order ID</span>
                <strong>#{{ $orderId ?? ($order->order_id ?? $order->id) }}</strong>
            </div>

            <div class="summary-details">
                @if(isset($order) && $order)
                <div class="detail-row">
                    <span class="detail-label">Product</span>
                    <span class="detail-value">{{ $order->product->title ?? 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Amount</span>
                    <span class="detail-value" style="color:var(--primary);font-weight:700;">৳{{ number_format($order->amount ?? $order->product->effective_price ?? 0, 2) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Payment Method</span>
                    <span class="detail-value">{{ ucfirst($order->payment_method ?? 'N/A') }}</span>
                </div>
                @endif
                <div class="detail-row">
                    <span class="detail-label">Order Status</span>
                    <span class="detail-value"><span class="status-badge">PENDING</span></span>
                </div>
            </div>

            <a href="{{ route('order.track') }}" class="btn-track-order">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M1 9h16M13 5l4 4-4 4"/></svg>
                Track Your Order
            </a>

            <p class="success-note">
                Save your Order ID for tracking. You will receive confirmation once your payment is verified.
            </p>
        </div>
    </div>
</div>
@endsection
