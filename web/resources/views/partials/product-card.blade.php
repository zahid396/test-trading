@props(['product'])
@php
    $isAvailable = ($product->status ?? 'available') === 'available';
@endphp
<div class="pcard" onclick="openProductModal({{ $product->id }})">
    <div class="pcard-img">
        @if($product->image)
            <img src="{{ asset('storage/products/' . $product->image) }}" alt="{{ $product->title }}" loading="lazy">
        @else
            <div class="pcard-placeholder">
                <svg width="48" height="48" fill="none" stroke="#9ca3af" stroke-width="1.5"><rect x="6" y="6" width="36" height="36" rx="4"/><circle cx="18" cy="18" r="4"/><path d="M6 32l10-10 8 8 6-6 12 12"/></svg>
            </div>
        @endif
        @if($product->discount_percentage && $product->discount_percentage > 0)
            <span class="pcard-badge">-{{ $product->discount_percentage }}%</span>
        @endif
        @if(!$isAvailable)
            <span class="pcard-unavailable">Sold Out</span>
        @endif
    </div>
    <div class="pcard-body">
        <h3 class="pcard-title">{{ $product->title }}</h3>
        @if($product->subtitle)
            <p class="pcard-subtitle">{{ Str::limit($product->subtitle, 60) }}</p>
        @endif
        <div class="pcard-price">
            <span class="pcard-current">৳{{ number_format($product->effective_price, 2) }}</span>
            @if($product->old_price && $product->old_price > $product->price)
                <span class="pcard-old">৳{{ number_format($product->old_price, 2) }}</span>
            @endif
        </div>
        <button class="pcard-btn" {{ !$isAvailable ? 'disabled' : '' }}>
            {{ $isAvailable ? 'View Product' : 'Unavailable' }}
        </button>
    </div>
</div>
