@extends('admin.layouts.app')

@section('title', 'Reviews')

@section('content')
    <div class="page-header" style="display:flex; align-items:center; justify-content:space-between;">
        <div>
            <h1>Reviews</h1>
            <p>Manage customer reviews and testimonials.</p>
        </div>
        <a href="{{ route('admin.reviews.create') }}" class="btn btn-primary">&#43; Add Review</a>
    </div>

    <div class="card">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Avatar</th>
                        <th>Customer</th>
                        <th>Product</th>
                        <th>Rating</th>
                        <th>Review</th>
                        <th>Featured</th>
                        <th>Active</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                        <tr>
                            <td>
                                @if($review->customer_avatar)
                                    <img src="{{ asset('storage/reviews/' . $review->customer_avatar) }}" class="thumb thumb-sm">
                                @else
                                    <div class="thumb thumb-sm thumb-placeholder">{{ strtoupper(substr($review->customer_name, 0, 1)) }}</div>
                                @endif
                            </td>
                            <td style="font-weight:500;">{{ $review->customer_name }}</td>
                            <td>{{ Str::limit($review->product->title ?? 'N/A', 30) }}</td>
                            <td>
                                <div class="stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="star {{ $i <= $review->rating ? 'filled' : '' }}">&#9733;</span>
                                    @endfor
                                </div>
                            </td>
                            <td>{{ Str::limit($review->review_text, 60) }}</td>
                            <td>
                                <label class="toggle">
                                    <input type="checkbox"
                                           {{ $review->is_featured ? 'checked' : '' }}
                                           onchange="toggleActive(this)"
                                           data-url="{{ route('admin.reviews.feature', $review) }}">
                                    <span class="toggle-slider"></span>
                                </label>
                            </td>
                            <td>
                                <label class="toggle">
                                    <input type="checkbox"
                                           {{ $review->is_active ? 'checked' : '' }}
                                           onchange="toggleActive(this)"
                                           data-url="{{ route('admin.reviews.toggle', $review) }}">
                                    <span class="toggle-slider"></span>
                                </label>
                            </td>
                            <td>
                                <div class="actions-cell btn-group">
                                    <a href="{{ route('admin.reviews.edit', $review) }}" class="btn btn-sm btn-outline">Edit</a>
                                    <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" onsubmit="return deleteConfirm(this)">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <div class="empty-state-icon">&#9734;</div>
                                    <h3>No reviews found</h3>
                                    <p>Add customer reviews to increase trust and conversions.</p>
                                    <a href="{{ route('admin.reviews.create') }}" class="btn btn-primary btn-sm">Add Review</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($reviews->hasPages())
            {{ $reviews->links('vendor.pagination.admin') }}
        @endif
    </div>
@endsection
