@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="page-header">
        <h1>Welcome back, {{ $authAdmin->name ?? 'Admin' }}</h1>
        <p>Here's what's happening with your store today.</p>
    </div>

    <div class="stats-grid">
        <a href="{{ route('admin.products.index') }}" class="stat-card">
            <div class="stat-icon primary">&#9733;</div>
            <div class="stat-content">
                <h4>{{ $totalProducts }}</h4>
                <p>Total Products</p>
            </div>
        </a>
        <a href="{{ route('admin.products.index') }}" class="stat-card">
            <div class="stat-icon success">&#10003;</div>
            <div class="stat-content">
                <h4>{{ $activeProducts }}</h4>
                <p>Active Products</p>
            </div>
        </a>
        <a href="{{ route('admin.orders.index') }}" class="stat-card">
            <div class="stat-icon info">&#9993;</div>
            <div class="stat-content">
                <h4>{{ $totalOrders }}</h4>
                <p>Total Orders</p>
            </div>
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="stat-card">
            <div class="stat-icon warning">&#9888;</div>
            <div class="stat-content">
                <h4>{{ $pendingOrders }}</h4>
                <p>Pending</p>
            </div>
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'verified']) }}" class="stat-card">
            <div class="stat-icon info">&#10004;</div>
            <div class="stat-content">
                <h4>{{ $verifiedOrders }}</h4>
                <p>Verified</p>
            </div>
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'delivered']) }}" class="stat-card">
            <div class="stat-icon success">&#11088;</div>
            <div class="stat-content">
                <h4>{{ $deliveredOrders }}</h4>
                <p>Delivered</p>
            </div>
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'rejected']) }}" class="stat-card">
            <div class="stat-icon danger">&#10007;</div>
            <div class="stat-content">
                <h4>{{ $rejectedOrders }}</h4>
                <p>Rejected</p>
            </div>
        </a>
        <a href="{{ route('admin.orders.index') }}" class="stat-card">
            <div class="stat-icon success">&#36;</div>
            <div class="stat-content">
                <h4>{{ number_format($totalRevenue, 2) }}</h4>
                <p>Total Revenue</p>
            </div>
        </a>
        <a href="{{ route('admin.reviews.index') }}" class="stat-card">
            <div class="stat-icon warning">&#9734;</div>
            <div class="stat-content">
                <h4>{{ $totalReviews }}</h4>
                <p>Total Reviews</p>
            </div>
        </a>
    </div>

    <div class="dash-grid">
        <div class="card">
            <div class="card-header">
                <h3>Recent Orders</h3>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline">View All</a>
            </div>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Product</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                            <tr>
                                <td>#{{ $order->order_id }}</td>
                                <td>{{ Str::limit($order->product->title ?? 'N/A', 20) }}</td>
                                <td>{{ $order->customer_email }}</td>
                                <td>{{ number_format($order->amount, 2) }}</td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'pending' => 'badge-warning',
                                            'verified' => 'badge-info',
                                            'delivered' => 'badge-success',
                                            'rejected' => 'badge-danger',
                                        ];
                                    @endphp
                                    <span class="badge {{ $statusColors[$order->status] ?? 'badge-secondary' }}">{{ ucfirst($order->status) }}</span>
                                </td>
                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <div class="empty-state-icon">&#9993;</div>
                                        <h3>No orders yet</h3>
                                        <p>Orders placed by customers will appear here.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>Recent Reviews</h3>
                <a href="{{ route('admin.reviews.index') }}" class="btn btn-sm btn-outline">View All</a>
            </div>
            <div class="card-body" style="padding-top:0;">
                @forelse($recentReviews as $review)
                    <div style="padding:1rem 0; border-bottom:1px solid var(--border);">
                        <div style="display:flex; align-items:center; justify-content:space-between;">
                            <span style="font-weight:600; font-size:0.875rem;">{{ $review->customer_name }}</span>
                            <div class="stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="star {{ $i <= $review->rating ? 'filled' : '' }}">&#9733;</span>
                                @endfor
                            </div>
                        </div>
                        <p style="font-size:0.8125rem; color:var(--text-secondary); margin-top:0.25rem;">
                            {{ Str::limit($review->review_text, 120) }}
                        </p>
                        <p style="font-size:0.75rem; color:#94a3b8; margin-top:0.25rem;">
                            on {{ $review->product->title ?? 'N/A' }}
                        </p>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="empty-state-icon">&#9734;</div>
                        <h3>No reviews yet</h3>
                        <p>Customer reviews will appear here.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="card" style="margin-top:1.5rem;">
        <div class="card-header">
            <h3>Quick Actions</h3>
        </div>
        <div class="card-body">
            <div class="quick-actions">
                <a href="{{ route('admin.products.create') }}" class="quick-action">
                    <div class="quick-action-icon">&#43;</div>
                    Add Product
                </a>
                <a href="{{ route('admin.banners.create') }}" class="quick-action">
                    <div class="quick-action-icon">&#9654;</div>
                    Add Banner
                </a>
                <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="quick-action">
                    <div class="quick-action-icon">&#9888;</div>
                    View Pending Orders
                </a>
                <a href="{{ route('admin.reviews.create') }}" class="quick-action">
                    <div class="quick-action-icon">&#9733;</div>
                    Add Review
                </a>
                <a href="{{ route('admin.store-settings.index') }}" class="quick-action">
                    <div class="quick-action-icon">&#9881;</div>
                    Store Settings
                </a>
            </div>
        </div>
    </div>
@endsection
