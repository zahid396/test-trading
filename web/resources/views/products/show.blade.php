@extends('layouts.app')
@section('head')
@php
    $isAvailable = ($product->status ?? 'available') === 'available';
@endphp
<style>
.product-detail { padding: 2.5rem 0 4rem; }
.product-layout { display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: start; }
.product-image-wrap { position: sticky; top: 80px; }
.product-image { width: 100%; aspect-ratio: 4/3; border-radius: var(--radius-xl); overflow: hidden; background: var(--gray-100); box-shadow: var(--shadow-md); border: 1px solid #223050; }
.product-image img { width: 100%; height: 100%; object-fit: cover; }
.product-placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: var(--gray-100); border-radius: var(--radius-xl); }
.product-info h1 { font-size: 1.75rem; font-weight: 800; color: var(--gray-900); margin-bottom: .25rem; line-height: 1.3; }
.product-subtitle { color: var(--gray-500); font-size: 1rem; margin-bottom: 1.25rem; }
.product-price-block { display: flex; align-items: baseline; gap: .75rem; margin-bottom: .25rem; flex-wrap: wrap; }
.product-price { font-size: 1.75rem; font-weight: 800; color: var(--primary); }
.product-old-price { font-size: 1.1rem; color: var(--gray-400); text-decoration: line-through; }
.product-discount-tag { background: rgba(246,70,93,.14); color: #f6465d; font-size: .82rem; font-weight: 700; padding: .25rem .7rem; border-radius: 50px; }
.product-status { display: inline-block; padding: .3rem .85rem; border-radius: 50px; font-size: .82rem; font-weight: 600; margin-top: .75rem; }
.product-status.available { background: rgba(22,199,132,.14); color: #16c784; border: 1px solid rgba(22,199,132,.3); }
.product-status.unavailable { background: rgba(246,70,93,.14); color: #f6465d; border: 1px solid rgba(246,70,93,.3); }
.product-divider { border: none; border-top: 1px solid var(--gray-200); margin: 1.5rem 0; }
.product-description h2 { font-size: 1.15rem; font-weight: 700; color: var(--gray-900); margin-bottom: .75rem; }
.product-description .desc-text { color: var(--gray-600); line-height: 1.8; font-size: .95rem; }
.desc-truncated { max-height: 8rem; overflow: hidden; position: relative; }
.desc-truncated::after { content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 3rem; background: linear-gradient(transparent, var(--white)); }
.read-more-toggle { background: none; border: none; color: var(--primary); font-weight: 600; font-size: .88rem; cursor: pointer; padding: 0; margin-bottom: 1rem; margin-top: .5rem; }
.product-features h2 { font-size: 1.15rem; font-weight: 700; color: var(--gray-900); margin-bottom: .75rem; }
.feature-list { list-style: none; }
.feature-list li { padding: .45rem 0; font-size: .95rem; color: var(--gray-600); display: flex; align-items: flex-start; gap: .6rem; }
.feature-list li .check { color: var(--accent); font-weight: 700; font-size: 1rem; flex-shrink: 0; margin-top: 1px; }
.product-actions { display: flex; gap: .75rem; margin-top: 2rem; flex-wrap: wrap; }
.btn-buy { flex: 1; padding: .9rem 1.5rem; background: linear-gradient(135deg, #f0b90b, #e2a008); color: #0b0f1c; font-family: var(--font-display); font-weight: 700; font-size: 1rem; border: none; border-radius: var(--radius); cursor: pointer; text-align: center; transition: var(--transition); text-decoration: none; display: flex; align-items: center; justify-content: center; gap: .5rem; box-shadow: 0 6px 20px rgba(240,185,11,.25); }
.btn-buy:hover { opacity: .94; transform: translateY(-1px); color: #0b0f1c; }
.btn-track { padding: .9rem 1.5rem; background: var(--gray-100); color: var(--gray-700); font-weight: 600; font-size: .95rem; border: 1px solid var(--gray-200); border-radius: var(--radius); cursor: pointer; text-decoration: none; transition: var(--transition); display: flex; align-items: center; gap: .4rem; }
.btn-track:hover { border-color: var(--primary); color: var(--primary); background: var(--primary-50); }

/* Reviews */
.product-reviews { margin-top: 2.5rem; padding-top: 2rem; border-top: 1px solid var(--gray-200); }
.product-reviews h2 { font-size: 1.15rem; font-weight: 700; color: var(--gray-900); margin-bottom: 1rem; }
.review-item { background: var(--gray-50); border-radius: var(--radius); padding: 1.15rem; margin-bottom: .75rem; border: 1px solid var(--gray-100); }
.review-item-stars { color: #f59e0b; font-size: .9rem; margin-bottom: .35rem; }
.review-item-text { color: var(--gray-600); font-size: .9rem; line-height: 1.65; margin-bottom: .35rem; }
.review-item-author { font-weight: 600; font-size: .82rem; color: var(--gray-700); }
.no-reviews { color: var(--gray-400); font-size: .9rem; padding: 1.5rem; text-align: center; background: var(--gray-50); border-radius: var(--radius); border: 1px dashed var(--gray-200); }

/* Review form */
.review-form { margin-top: 2rem; }
.review-form h2 { font-size: 1.15rem; font-weight: 700; color: var(--gray-900); margin-bottom: 1rem; }
.review-form-card { background: var(--gray-50); border: 1px solid var(--gray-100); border-radius: var(--radius-lg); padding: 1.5rem; }
.review-form .form-group { margin-bottom: 1.15rem; }
.review-form .form-group label { display: block; font-size: .88rem; font-weight: 600; color: var(--gray-700); margin-bottom: .4rem; }
.review-form .form-group input[type="text"], .review-form .form-group textarea { width: 100%; padding: .75rem 1rem; border: 1px solid var(--gray-200); border-radius: var(--radius); font-size: .95rem; color: var(--gray-800); font-family: inherit; transition: var(--transition); background: var(--white); color-scheme: dark; }
.review-form .form-group textarea { min-height: 110px; resize: vertical; }
.review-form .form-group input[type="text"]:focus, .review-form .form-group textarea:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(240,185,11,.12); background: #18223c; }
.star-rating { display: flex; flex-direction: row-reverse; justify-content: flex-end; gap: .15rem; }
.star-rating input { display: none; }
.star-rating label { font-size: 1.7rem; color: #2c3a5e; cursor: pointer; line-height: 1; transition: var(--transition); }
.star-rating label:hover, .star-rating label:hover ~ label, .star-rating input:checked ~ label { color: #f59e0b; }
.review-success { display: flex; align-items: center; gap: .5rem; padding: .85rem 1rem; background: rgba(22,199,132,.12); border: 1px solid rgba(22,199,132,.32); border-radius: var(--radius); color: #34d399; font-size: .9rem; font-weight: 600; margin-bottom: 1rem; }
.form-error { color: #f6465d; font-size: .8rem; margin-top: .3rem; }
.review-submit { padding: .85rem 2rem; background: linear-gradient(135deg, #f0b90b, #e2a008); color: #0b0f1c; font-family: var(--font-display); font-weight: 700; font-size: .95rem; border: none; border-radius: var(--radius); cursor: pointer; transition: var(--transition); }
.review-submit:hover { opacity: .94; transform: translateY(-1px); }
.review-note { margin-top: .85rem; font-size: .8rem; color: var(--gray-400); }

@media (max-width: 768px) {
    .product-layout { grid-template-columns: 1fr; gap: 1.5rem; }
    .product-image-wrap { position: static; }
    .product-info h1 { font-size: 1.35rem; }
    .product-actions { flex-direction: column; }
    .btn-track { justify-content: center; }
    .star-rating label { font-size: 1.5rem; }
    .review-form-card { padding: 1.15rem; }
}
</style>
@endsection

@section('content')
<div class="product-detail">
    <div class="container">
        <div class="product-layout">
            <div class="product-image-wrap">
                <div class="product-image">
                    @if($product->image)
                        <img src="{{ asset('storage/products/' . $product->image) }}" alt="{{ $product->title }}">
                    @else
                        <div class="product-placeholder">
                            <svg width="80" height="80" fill="none" stroke="#3a4a6e" stroke-width="1.5"><rect x="8" y="8" width="64" height="64" rx="8"/><circle cx="28" cy="28" r="10"/><path d="M8 56l20-20 16 16 10-10 18 18"/></svg>
                        </div>
                    @endif
                </div>
            </div>
            <div class="product-info">
                <h1>{{ $product->title }}</h1>
                @if($product->subtitle)
                    <p class="product-subtitle">{{ $product->subtitle }}</p>
                @endif
                <div class="product-price-block">
                    <span class="product-price">৳{{ number_format($product->effective_price, 2) }}</span>
                    @if($product->old_price && $product->old_price > $product->price)
                        <span class="product-old-price">৳{{ number_format($product->old_price, 2) }}</span>
                    @endif
                    @if($product->discount_percentage && $product->discount_percentage > 0)
                        <span class="product-discount-tag">-{{ $product->discount_percentage }}% OFF</span>
                    @endif
                </div>
                <span class="product-status {{ $isAvailable ? 'available' : 'unavailable' }}">
                    {{ $isAvailable ? '✓ Available' : '✗ Sold Out' }}
                </span>

                <hr class="product-divider">

                @if(!empty($product->description))
                <div class="product-description">
                    <h2>Description</h2>
                    <div class="desc-text desc-truncated" id="descText">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                    <button class="read-more-toggle" id="readMoreBtn" onclick="var t=document.getElementById('descText');t.classList.toggle('desc-truncated');this.textContent=t.classList.contains('desc-truncated')?'Read More →':'Show Less ↑';">Read More →</button>
                </div>
                @endif

                @if(!empty($product->features) && (is_array($product->features) || count($product->features)))
                <div class="product-features">
                    <h2>Features</h2>
                    <ul class="feature-list">
                        @foreach($product->features as $feature)
                            <li><span class="check">✓</span> {{ is_object($feature) ? $feature->text ?? $feature->feature ?? $feature->title ?? '' : $feature }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="product-actions">
                    @if($isAvailable)
                        <a href="{{ route('checkout.show', $product->id) }}" class="btn-buy">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M1 1h4l2.68 13.39a1 1 0 0 0 1 .61h9.72a1 1 0 0 0 1-.76L21 6H6"/><circle cx="9" cy="21" r="1.5"/><circle cx="18" cy="21" r="1.5"/></svg>
                            Buy Now — ৳{{ number_format($product->effective_price, 2) }}
                        </a>
                    @else
                        <button class="btn-buy" disabled style="opacity:.5;cursor:not-allowed;">Sold Out</button>
                    @endif
                    <a href="{{ route('order.track') }}" class="btn-track">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M1 8h14M11 4l4 4-4 4"/></svg>
                        Track Order
                    </a>
                </div>

                @if(isset($product->reviews) && $product->reviews->count())
                <div class="product-reviews">
                    <h2>Customer Reviews</h2>
                    @foreach($product->reviews as $review)
                        <div class="review-item">
                            <div class="review-item-stars">{{ str_repeat('★', $review->rating ?? 5) }}{{ str_repeat('☆', max(0, 5 - ($review->rating ?? 5))) }}</div>
                            <p class="review-item-text">{{ $review->review_text ?? '' }}</p>
                            <div class="review-item-author">{{ $review->customer_name ?? 'Customer' }}</div>
                        </div>
                    @endforeach
                </div>
                @else
                <div class="no-reviews">No reviews yet — be the first to review this product.</div>
                @endif

                <div class="review-form">
                    <h2>Write a Review</h2>
                    <div class="review-form-card">
                        @if(session('review_success'))
                            <div class="review-success">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="9" cy="9" r="8"/><path d="M6 9l2 2 4-4"/></svg>
                                {{ session('review_success') }}
                            </div>
                        @endif
                        <form method="POST" action="{{ route('reviews.store', $product->id) }}">
                            @csrf
                            <div class="form-group">
                                <label>Your Name <span style="color:var(--down);">*</span></label>
                                <input type="text" name="customer_name" value="{{ old('customer_name') }}" placeholder="e.g. John Doe" required maxlength="100">
                                @error('customer_name') <div class="form-error">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-group">
                                <label>Your Rating <span style="color:var(--down);">*</span></label>
                                <div class="star-rating" id="starRating">
                                    @for($i = 5; $i >= 1; $i--)
                                        <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" {{ old('rating') == $i ? 'checked' : '' }}>
                                        <label for="star{{ $i }}" title="{{ $i }} star{{ $i > 1 ? 's' : '' }}">★</label>
                                    @endfor
                                </div>
                                @error('rating') <div class="form-error">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-group">
                                <label>Your Review <span style="color:var(--down);">*</span></label>
                                <textarea name="review_text" placeholder="Share your experience with this product..." required maxlength="1000">{{ old('review_text') }}</textarea>
                                @error('review_text') <div class="form-error">{{ $message }}</div> @enderror
                            </div>
                            <button type="submit" class="review-submit">Submit Review</button>
                            <p class="review-note">Your review will appear after admin approval.</p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
