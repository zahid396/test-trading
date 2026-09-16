@extends('layouts.app')
@section('head')
@php
    $isAvailable = ($product->status ?? 'available') === 'available';
    $bkashActive  = !empty($paymentMethods['bkash']) && $paymentMethods['bkash']->is_active;
    $nagadActive  = !empty($paymentMethods['nagad']) && $paymentMethods['nagad']->is_active;
    $defaultMethod = $bkashActive ? 'bkash' : (!$nagadActive ? 'bkash' : 'nagad');
@endphp
<style>
.checkout-page { padding: 2.5rem 0 4rem; }
.checkout-grid { display: grid; grid-template-columns: 1.2fr .8fr; gap: 2rem; align-items: start; }
.checkout-card { background: var(--white); border-radius: var(--radius-lg); padding: 1.75rem; box-shadow: var(--shadow); border: 1px solid var(--gray-100); }
.checkout-card h2 { font-size: 1.1rem; font-weight: 700; color: var(--gray-900); margin-bottom: 1.25rem; display: flex; align-items: center; gap: .5rem; }
.checkout-card h2 .step { display: inline-flex; align-items: center; justify-content: center; width: 26px; height: 26px; border-radius: 50%; background: var(--primary); color: var(--white); font-size: .78rem; font-weight: 700; }

/* Product summary */
.checkout-product { display: flex; gap: 1rem; align-items: center; padding-bottom: 1.25rem; border-bottom: 1px solid var(--gray-100); margin-bottom: 1.25rem; }
.checkout-thumb { width: 80px; height: 60px; border-radius: var(--radius); overflow: hidden; background: var(--gray-100); flex-shrink: 0; }
.checkout-thumb img { width: 100%; height: 100%; object-fit: cover; }
.checkout-product-info h3 { font-size: .95rem; font-weight: 700; color: var(--gray-900); margin-bottom: .2rem; }
.checkout-product-info p { font-size: .85rem; color: var(--gray-500); }

/* Payment methods */
.payment-options { display: flex; flex-direction: column; gap: .75rem; margin-bottom: 1.25rem; }
.payment-option { display: flex; align-items: center; gap: .75rem; padding: 1rem; border: 2px solid var(--gray-200); border-radius: var(--radius); cursor: pointer; transition: var(--transition); }
.payment-option:hover { border-color: var(--gray-300); }
.payment-option.selected { border-color: var(--primary); background: var(--primary-50); }
.payment-option input[type="radio"] { display: none; }
.payment-option .radio-custom { width: 20px; height: 20px; border: 2px solid var(--gray-300); border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: var(--transition); }
.payment-option.selected .radio-custom { border-color: var(--primary); }
.payment-option.selected .radio-custom::after { content: ''; width: 10px; height: 10px; background: var(--primary); border-radius: 50%; }
.payment-label { font-weight: 600; font-size: .95rem; color: var(--gray-800); }
.payment-sublabel { font-size: .8rem; color: var(--gray-500); }
.payment-logo { height: 36px; width: auto; max-width: 120px; object-fit: contain; margin-right: .25rem; }

/* Payment instructions */
.payment-instructions { background: var(--gray-50); border-radius: var(--radius); padding: 1.25rem; margin-bottom: 1.25rem; border: 1px solid var(--gray-100); }
.payment-instructions h4 { font-size: .9rem; font-weight: 700; color: var(--gray-800); margin-bottom: .75rem; }
.payment-instructions .method-number { font-size: 1.1rem; font-weight: 800; color: var(--primary); margin-bottom: .5rem; background: var(--white); display: inline-block; padding: .35rem .85rem; border-radius: var(--radius); border: 1px dashed var(--primary-light); }
.payment-instructions .method-type { font-size: .82rem; color: var(--gray-500); margin-bottom: .75rem; }
.payment-instructions ol { padding-left: 1.25rem; color: var(--gray-600); font-size: .9rem; line-height: 1.75; }
.payment-instructions ol li { margin-bottom: .25rem; }
.payment-qr { margin-top: .75rem; }
.payment-qr img { width: 160px; height: 160px; object-fit: contain; border-radius: var(--radius); border: 1px solid var(--gray-200); }
.hidden { display: none !important; }

/* Form */
.form-group { margin-bottom: 1.15rem; }
.form-group label { display: block; font-size: .88rem; font-weight: 600; color: var(--gray-700); margin-bottom: .4rem; }
.form-group label .required { color: var(--down); }
.form-group input { width: 100%; padding: .75rem 1rem; border: 1px solid var(--gray-200); border-radius: var(--radius); font-size: .95rem; color: var(--gray-800); transition: var(--transition); background: var(--white); color-scheme: dark; }
.form-group input:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(240,185,11,.12); background: #18223c; }
.form-group input::placeholder { color: var(--gray-400); }
.form-error { color: #f6465d; font-size: .8rem; margin-top: .3rem; }

.btn-submit { width: 100%; padding: .9rem; background: linear-gradient(135deg, #f0b90b, #e2a008); color: #0b0f1c; font-family: var(--font-display); font-weight: 700; font-size: 1rem; border: none; border-radius: var(--radius); cursor: pointer; transition: var(--transition); margin-top: .5rem; box-shadow: 0 6px 20px rgba(240,185,11,.25); }
.btn-submit:hover { opacity: .94; transform: translateY(-1px); }
.btn-submit:disabled { opacity: .5; cursor: not-allowed; transform: none; }
.expiry-notice { display: flex; align-items: center; gap: .5rem; margin-top: 1rem; padding: .85rem; background: rgba(245,158,11,.12); border: 1px solid rgba(245,158,11,.36); border-radius: var(--radius); font-size: .82rem; color: #fbbf24; }
.expiry-notice svg { flex-shrink: 0; }

/* Order summary sidebar */
.order-summary .summary-row { display: flex; justify-content: space-between; padding: .6rem 0; font-size: .9rem; color: var(--gray-600); }
.order-summary .summary-total { border-top: 2px solid var(--gray-200); margin-top: .5rem; padding-top: .75rem; font-weight: 800; font-size: 1.05rem; color: var(--gray-900); }
.order-summary .summary-total span:last-child { color: var(--primary); }
.session-timer { margin-top: 1rem; text-align: center; padding: 1rem; background: var(--gray-50); border-radius: var(--radius); border: 1px solid var(--gray-100); }
.session-timer p { font-size: .82rem; color: var(--gray-500); }
.session-timer .timer { font-size: 1.4rem; font-weight: 800; color: var(--primary); margin-top: .35rem; }

@media (max-width: 768px) {
    .checkout-grid { grid-template-columns: 1fr; }
    .checkout-page { padding: 1.5rem 0 3rem; }
}
</style>
@endsection

@section('content')
<div class="checkout-page">
    <div class="container">
        <div class="products-header">
            <h1 style="font-size:1.5rem;font-weight:800;color:var(--gray-900);">Checkout</h1>
            <p style="color:var(--gray-500);margin-top:.3rem;">Complete your purchase securely</p>
        </div>

        <form method="POST" action="{{ route('checkout.submit') }}" id="checkoutForm" style="margin-top:1.5rem;">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="session_id" value="{{ $session->session_id ?? $sessionId ?? session('checkout_session_id') ?? '' }}">

            @if(session('error'))
            <div class="expiry-notice" style="background:rgba(246,70,93,.12);border-color:rgba(246,70,93,.35);color:#f6465d;margin-bottom:1.25rem;">
                <span>{{ session('error') }}</span>
            </div>
            @endif

            <div class="checkout-grid">
                <div>
                    <!-- Payment Method -->
                    <div class="checkout-card" style="margin-bottom:1.25rem;">
                        <h2><span class="step">1</span> Select Payment Method</h2>
                        @unless($bkashActive || $nagadActive)
                        <div class="expiry-notice" style="background:rgba(246,70,93,.12);border-color:rgba(246,70,93,.35);color:#f6465d;">
                            <span>No payment methods are currently available. Please contact support.</span>
                        </div>
                        @endunless
                        <div class="payment-options">
                            @if($bkashActive)
                            <label class="payment-option {{ $defaultMethod==='bkash'?'selected':'' }}" data-method="bkash" onclick="selectPayment(this)">
                                <input type="radio" name="payment_method" value="bkash" {{ $defaultMethod==='bkash'?'checked':'' }}>
                                <div class="radio-custom"></div>
                                @if(!empty($paymentMethods['bkash']->logo))
                                    <img src="{{ asset('storage/payments/' . $paymentMethods['bkash']->logo) }}" alt="bKash" class="payment-logo">
                                @endif
                                <div>
                                    <div class="payment-label">bKash</div>
                                    <div class="payment-sublabel">Send money via bKash</div>
                                </div>
                            </label>
                            @endif
                            @if($nagadActive)
                            <label class="payment-option {{ $defaultMethod==='nagad'?'selected':'' }}" data-method="nagad" onclick="selectPayment(this)">
                                <input type="radio" name="payment_method" value="nagad" {{ $defaultMethod==='nagad'?'checked':'' }}>
                                <div class="radio-custom"></div>
                                @if(!empty($paymentMethods['nagad']->logo))
                                    <img src="{{ asset('storage/payments/' . $paymentMethods['nagad']->logo) }}" alt="Nagad" class="payment-logo">
                                @endif
                                <div>
                                    <div class="payment-label">Nagad</div>
                                    <div class="payment-sublabel">Send money via Nagad</div>
                                </div>
                            </label>
                            @endif
                        </div>

                        @if($bkashActive)
                        <div class="payment-instructions {{ $defaultMethod==='bkash'?'':'hidden' }}" id="instructions-bkash">
                            <h4>bKash Payment Instructions</h4>
                            <div class="method-number">{{ $paymentMethods['bkash']->number }}</div>
                            <div class="method-type">Account Type: {{ ucfirst($paymentMethods['bkash']->account_type) }}</div>
                            @if(!empty($paymentMethods['bkash']->instructions))
                                <ol>
                                    @foreach(explode("\n", $paymentMethods['bkash']->instructions) as $step)
                                        @if(trim($step))
                                            <li>{{ trim($step) }}</li>
                                        @endif
                                    @endforeach
                                </ol>
                            @else
                                <ol>
                                    <li>Open your bKash app</li>
                                    <li>Tap "Send Money"</li>
                                    <li>Enter the number above</li>
                                    <li>Enter amount: ৳{{ number_format($product->effective_price, 2) }}</li>
                                    <li>Use Order ID as reference</li>
                                </ol>
                            @endif
                            @if(!empty($paymentMethods['bkash']->qr_image))
                            <div class="payment-qr">
                                <img src="{{ asset('storage/payments/' . $paymentMethods['bkash']->qr_image) }}" alt="bKash QR Code">
                            </div>
                            @endif
                        </div>
                        @endif

                        @if($nagadActive)
                        <div class="payment-instructions {{ $defaultMethod==='nagad'?'':'hidden' }}" id="instructions-nagad">
                            <h4>Nagad Payment Instructions</h4>
                            <div class="method-number">{{ $paymentMethods['nagad']->number }}</div>
                            <div class="method-type">Account Type: {{ ucfirst($paymentMethods['nagad']->account_type) }}</div>
                            @if(!empty($paymentMethods['nagad']->instructions))
                                <ol>
                                    @foreach(explode("\n", $paymentMethods['nagad']->instructions) as $step)
                                        @if(trim($step))
                                            <li>{{ trim($step) }}</li>
                                        @endif
                                    @endforeach
                                </ol>
                            @else
                                <ol>
                                    <li>Open your Nagad app</li>
                                    <li>Tap "Send Money"</li>
                                    <li>Enter the number above</li>
                                    <li>Enter amount: ৳{{ number_format($product->effective_price, 2) }}</li>
                                    <li>Use Order ID as reference</li>
                                </ol>
                            @endif
                            @if(!empty($paymentMethods['nagad']->qr_image))
                            <div class="payment-qr">
                                <img src="{{ asset('storage/payments/' . $paymentMethods['nagad']->qr_image) }}" alt="Nagad QR Code">
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>

                    <!-- Payment Details Form -->
                    <div class="checkout-card">
                        <h2><span class="step">2</span> Payment Details</h2>
                        <div class="form-group">
                            <label>Your Email <span class="required">*</span></label>
                            <input type="email" name="customer_email" value="{{ old('customer_email') }}" placeholder="you@example.com" required>
                            @error('customer_email') <div class="form-error">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group">
                            <label>Sender Number <span class="required">*</span></label>
                            <input type="text" name="sender_number" value="{{ old('sender_number') }}" placeholder="01XXXXXXXXX" required pattern="01[0-9]{9}" maxlength="11">
                            @error('sender_number') <div class="form-error">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group">
                            <label>Transaction ID <span class="required">*</span></label>
                            <input type="text" name="transaction_id" value="{{ old('transaction_id') }}" placeholder="e.g. 9SA8F7G5H2" required>
                            @error('transaction_id') <div class="form-error">{{ $message }}</div> @enderror
                        </div>
                        <button type="submit" class="btn-submit" id="submitBtn">Submit Order — ৳{{ number_format($product->effective_price, 2) }}</button>
                        <div class="expiry-notice">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="9" cy="9" r="8"/><path d="M9 5v4l3 2"/></svg>
                            <span>This payment session expires in <strong>30 minutes</strong>. Please complete your payment promptly.</span>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div>
                    <div class="checkout-card order-summary" style="position:sticky;top:80px;">
                        <h2><span class="step">💰</span> Order Summary</h2>
                        <div class="checkout-product">
                            <div class="checkout-thumb">
                                @if($product->image)
                                    <img src="{{ asset('storage/products/' . $product->image) }}" alt="{{ $product->title }}">
                                @endif
                            </div>
                            <div class="checkout-product-info">
                                <h3>{{ $product->title }}</h3>
                                @if($product->subtitle)
                                    <p>{{ Str::limit($product->subtitle, 50) }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="summary-row">
                            <span>Price</span>
                            <span>৳{{ number_format($product->effective_price, 2) }}</span>
                        </div>
                        @if($product->old_price && $product->old_price > $product->price)
                        <div class="summary-row" style="color:var(--accent);font-weight:600;">
                            <span>Discount</span>
                            <span>-৳{{ number_format($product->old_price - $product->effective_price, 2) }}</span>
                        </div>
                        @endif
                        <div class="summary-row">
                            <span>Platform Fee</span>
                            <span>Free</span>
                        </div>
                        <div class="summary-row summary-total">
                            <span>Total</span>
                            <span>৳{{ number_format($product->effective_price, 2) }}</span>
                        </div>
                        <div class="session-timer" id="sessionTimer">
                            <p>Session expires in</p>
                            <div class="timer" id="timerDisplay">30:00</div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
(function(){
    var remaining=30*60;
    var timerEl=document.getElementById('timerDisplay');
    var countdown=setInterval(function(){
        remaining--;
        if(remaining<=0){clearInterval(countdown);timerEl.textContent='Expired';timerEl.style.color='#f6465d';return;}
        var m=Math.floor(remaining/60),s=remaining%60;
        timerEl.textContent=(m<10?'0':'')+m+':'+(s<10?'0':'')+s;
    },1000);

    window.selectPayment=function(el){
        document.querySelectorAll('.payment-option').forEach(function(o){o.classList.remove('selected');});
        el.classList.add('selected');
        el.querySelector('input[type="radio"]').checked=true;
        var method=el.dataset.method;
        document.querySelectorAll('.payment-instructions').forEach(function(i){i.classList.add('hidden');});
        var panel=document.getElementById('instructions-'+method);
        if(panel)panel.classList.remove('hidden');
    };

    var form=document.getElementById('checkoutForm');
    form.addEventListener('submit',function(){
        document.getElementById('submitBtn').disabled=true;
        document.getElementById('submitBtn').textContent='Processing...';
    });
})();
</script>
@endsection
