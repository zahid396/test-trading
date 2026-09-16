@extends('layouts.app')
@section('head')
<style>
.track-page { padding: 2.5rem 0 4rem; }
.track-page h1 { font-size: 1.5rem; font-weight: 800; color: var(--gray-900); text-align: center; margin-bottom: .5rem; }
.track-page .subtitle { text-align: center; color: var(--gray-500); margin-bottom: 2rem; }

/* Search form */
.track-search { max-width: 480px; margin: 0 auto 3rem; background: var(--white); border-radius: var(--radius-lg); padding: 2rem; box-shadow: var(--shadow); border: 1px solid var(--gray-200); }
.track-search-form { display: flex; gap: .75rem; }
.track-search-form input { flex: 1; padding: .85rem 1rem; border: 1px solid var(--gray-200); border-radius: var(--radius); font-size: 1rem; color: var(--gray-800); transition: var(--transition); background: var(--gray-50); color-scheme: dark; }
.track-search-form input:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(240,185,11,.12); background: #18223c; }
.track-search-form input::placeholder { color: var(--gray-400); }
.track-search-form button { padding: .85rem 1.75rem; background: linear-gradient(135deg, #f0b90b, #e2a008); color: #0b0f1c; font-family: var(--font-display); font-weight: 700; font-size: .95rem; border: none; border-radius: var(--radius); cursor: pointer; transition: var(--transition); white-space: nowrap; }
.track-search-form button:hover { opacity: .94; }
.track-search .error-msg { color: #f6465d; font-size: .88rem; margin-top: .75rem; text-align: center; }
.track-search p { color: var(--gray-400); font-size: .85rem; text-align: center; margin-top: 1rem; }

/* Order result */
.track-result { max-width: 560px; margin: 0 auto; }
.order-info-card { background: var(--white); border-radius: var(--radius-lg); padding: 2rem; box-shadow: var(--shadow); border: 1px solid var(--gray-100); margin-bottom: 1.5rem; }
.order-info-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; padding-bottom: 1rem; border-bottom: 1px solid var(--gray-100); flex-wrap: wrap; gap: .5rem; }
.order-info-header h2 { font-size: 1rem; font-weight: 700; color: var(--gray-900); }
.order-info-header .oid { font-size: .9rem; font-weight: 600; color: var(--primary); background: var(--primary-50); padding: .3rem .75rem; border-radius: 50px; }
.info-rows { display: flex; flex-direction: column; gap: .5rem; }
.info-row { display: flex; justify-content: space-between; font-size: .9rem; }
.info-row .label { color: var(--gray-500); }
.info-row .value { font-weight: 600; color: var(--gray-800); }

/* Timeline */
.timeline { background: var(--white); border-radius: var(--radius-lg); padding: 2rem; box-shadow: var(--shadow); border: 1px solid var(--gray-100); }
.timeline h2 { font-size: 1rem; font-weight: 700; color: var(--gray-900); margin-bottom: 1.5rem; }
.timeline-steps { position: relative; padding-left: 2rem; }
.timeline-steps::before { content: ''; position: absolute; left: 11px; top: 8px; bottom: 8px; width: 2px; background: var(--gray-200); }
.timeline-step { position: relative; padding-bottom: 2rem; }
.timeline-step:last-child { padding-bottom: 0; }
.timeline-step .step-dot { position: absolute; left: -2rem; top: 2px; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: .7rem; z-index: 1; }
.timeline-step .step-dot.pending { background: var(--gray-200); color: var(--gray-400); }
.timeline-step .step-dot.active { background: rgba(245,158,11,.2); color: #fbbf24; box-shadow: 0 0 0 4px rgba(245,158,11,.16); border: 1px solid rgba(245,158,11,.4); }
.timeline-step .step-dot.done { background: var(--accent); color: #0b0f1c; box-shadow: 0 0 0 4px rgba(22,199,132,.12); }
.timeline-step .step-dot.rejected { background: rgba(246,70,93,.2); color: #f6465d; border: 1px solid rgba(246,70,93,.4); }
.timeline-step .step-label { font-weight: 600; font-size: .95rem; color: var(--gray-800); margin-bottom: .15rem; }
.timeline-step .step-time { font-size: .78rem; color: var(--gray-400); }
.timeline-step.pending .step-label { color: var(--gray-400); }
.timeline-step.rejected-step .step-label { color: #f6465d; }
.timeline-step.rejected-step .step-desc { color: #f6465d; font-size: .82rem; margin-top: .25rem; }

/* Status-specific summary */
.status-summary { text-align: center; padding: 1.5rem; border-radius: var(--radius); margin-bottom: 1.5rem; font-weight: 700; font-size: 1rem; border: 1px solid transparent; }
.status-summary.verified { background: rgba(22,199,132,.14); color: #16c784; border-color: rgba(22,199,132,.35); }
.status-summary.pending { background: rgba(245,158,11,.16); color: #fbbf24; border-color: rgba(245,158,11,.35); }
.status-summary.rejected { background: rgba(246,70,93,.14); color: #f6465d; border-color: rgba(246,70,93,.35); }

.back-link { display: inline-flex; align-items: center; gap: .4rem; color: var(--primary); font-weight: 600; font-size: .9rem; margin-top: 1rem; }
.back-link:hover { gap: .6rem; }

@media (max-width: 480px) {
    .track-search-form { flex-direction: column; }
    .order-info-card, .timeline { padding: 1.5rem 1.25rem; }
}
</style>
@endsection

@section('content')
<div class="track-page">
    <div class="container">
        <h1>Track Your Order</h1>
        <p class="subtitle">Enter your order ID to check the status</p>

        @if(!isset($order) || !$order)
        <div class="track-search">
            <form method="POST" action="{{ route('order.search') }}" class="track-search-form">
                @csrf
                <input type="text" name="order_id" placeholder="e.g. ORD-20260908-0001" value="{{ old('order_id', request('order_id')) }}" required spellcheck="false" autocomplete="off" maxlength="40">
                <button type="submit">Track</button>
            </form>
            @if(session('error'))
                <div class="error-msg">{{ session('error') }}</div>
            @endif
            <p>Find your Order ID in the confirmation email or on the success page after purchase.</p>
        </div>
        @else
        <div class="track-result">
            @php
                $status = $order->status ?? 'pending';
                $statusLower = strtolower($status);
                $steps = [
                    ['key'=>'received','label'=>'Order Received','desc'=>'Your order has been received and is queued for processing.'],
                    ['key'=>'verifying','label'=>'Payment Verification','desc'=>'We are verifying your payment details.'],
                    ['key'=>'verified','label'=>'Verified & Confirmed','desc'=>'Your payment has been verified. Your product will be delivered soon.'],
                    ['key'=>'delivered','label'=>'Delivered','desc'=>'Your product has been delivered successfully.'],
                ];
            @endphp

            <div class="status-summary {{ $statusLower }}">
                @if($statusLower === 'delivered')
                    ✓ Your order has been delivered!
                @elseif($statusLower === 'verified' || $statusLower === 'completed')
                    ✓ Your payment has been verified. Product delivery is in progress.
                @elseif($statusLower === 'rejected')
                    ✗ Your order could not be verified.
                    @if(!empty($order->rejection_reason))
                        <div style="margin-top:.5rem; font-weight:600; font-size:.9rem;">Reason: {{ $order->rejection_reason }}</div>
                    @endif
                @else
                    ⏳ Order received, awaiting verification.
                @endif
            </div>

            <div class="order-info-card">
                <div class="order-info-header">
                    <h2>Order Details</h2>
                    <span class="oid">#{{ $order->order_id ?? $order->id }}</span>
                </div>
                <div class="info-rows">
                    <div class="info-row"><span class="label">Product</span><span class="value">{{ $order->product->title ?? 'N/A' }}</span></div>
                    <div class="info-row"><span class="label">Amount</span><span class="value" style="color:var(--primary);">৳{{ number_format($order->amount ?? $order->product->effective_price ?? 0, 2) }}</span></div>
                    <div class="info-row"><span class="label">Payment Method</span><span class="value">{{ ucfirst($order->payment_method ?? 'N/A') }}</span></div>
                    <div class="info-row"><span class="label">Date</span><span class="value">{{ $order->created_at ? $order->created_at->format('d M Y, h:i A') : 'N/A' }}</span></div>
                    <div class="info-row"><span class="label">Status</span><span class="value"><span class="status-badge" style="background:{{ in_array($statusLower,['delivered','verified','completed'])?'rgba(22,199,132,.14)':($statusLower==='rejected'?'rgba(246,70,93,.14)':'rgba(245,158,11,.16)') }};color:{{ in_array($statusLower,['delivered','verified','completed'])?'#16c784':($statusLower==='rejected'?'#f6465d':'#fbbf24') }};">{{ strtoupper($status) }}</span></span></div>
                    @if($statusLower === 'rejected' && !empty($order->rejection_reason))
                        <div class="info-row"><span class="label">Rejection Reason</span><span class="value" style="color:#f6465d;">{{ $order->rejection_reason }}</span></div>
                    @endif
                </div>
            </div>

            <div class="timeline">
                <h2>Order Timeline</h2>
                <div class="timeline-steps">
                    @foreach($steps as $si => $step)
                        @php
                            $stepStatus = 'pending';
                            if($step['key'] === 'received') $stepStatus = 'done';
                            if($statusLower === 'delivered' && $step['key'] !== 'delivered') $stepStatus = 'done';
                            if($statusLower === 'delivered' && $step['key'] === 'delivered') $stepStatus = 'done';
                            if(($statusLower === 'verified' || $statusLower === 'completed') && in_array($step['key'], ['received','verifying'])) $stepStatus = 'done';
                            if(($statusLower === 'verified' || $statusLower === 'completed') && $step['key'] === 'verified') $stepStatus = 'active';
                            if($statusLower === 'pending' && $step['key'] === 'verifying') $stepStatus = 'active';
                            if($statusLower === 'rejected' && $step['key'] === 'verifying') $stepStatus = 'rejected';
                        @endphp
                        <div class="timeline-step {{ $stepStatus }} {{ $stepStatus==='rejected'?'rejected-step':'' }}">
                            <div class="step-dot {{ $stepStatus }}">
                                @if($stepStatus==='done')
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><polyline points="10 3 4 9 1 6"/></svg>
                                @elseif($stepStatus==='active')
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="3"><circle cx="6" cy="6" r="2"/></svg>
                                @elseif($stepStatus==='rejected')
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><path d="M2 2l8 8M10 2l-8 8"/></svg>
                                @else
                                    {{ $si + 1 }}
                                @endif
                            </div>
                            <div class="step-label">{{ $step['label'] }}</div>
                            @if($stepStatus==='active'||$stepStatus==='done'||$stepStatus==='rejected')
                                <div class="step-time">@if($step['key'] === 'verifying' && $statusLower === 'rejected' && !empty($order->rejection_reason)){{ $order->rejection_reason }}@else{{ $step['desc'] }}@endif</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <div style="text-align:center;">
                <a href="{{ route('order.track') }}" class="back-link">← Track Another Order</a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
