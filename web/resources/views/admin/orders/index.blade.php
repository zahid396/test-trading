@extends('admin.layouts.app')

@section('title', 'Orders')

@section('content')
    <div class="page-header">
        <h1>Orders</h1>
        <p>Manage all customer orders.</p>
    </div>

    @php
        $tabs = [
            '' => 'All',
            'pending' => 'Pending',
            'verified' => 'Verified',
            'delivered' => 'Delivered',
            'rejected' => 'Rejected',
        ];
        $counts = [
            '' => $orders->total(),
        ];
        $statusCounts = App\Models\Order::selectRaw("status, count(*) as total")->groupBy('status')->pluck('total', 'status')->all();
    @endphp

    <div class="filter-tabs">
        @foreach($tabs as $value => $label)
            <a href="{{ route('admin.orders.index', array_filter(['status' => $value ?: null])) }}"
               class="filter-tab {{ request('status') === $value ? 'active' : '' }}">
                {{ $label }}
                @if($value)
                    <span class="count">{{ $statusCounts[$value] ?? 0 }}</span>
                @else
                    <span class="count">{{ $counts[''] }}</span>
                @endif
            </a>
        @endforeach
    </div>

    <div class="card">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Product</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Payment</th>
                        <th>Sender No</th>
                        <th>Transaction</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td style="font-weight:500;">#{{ $order->order_id }}</td>
                            <td>{{ Str::limit($order->product->title ?? 'N/A', 25) }}</td>
                            <td>{{ $order->customer_email }}</td>
                            <td>{{ number_format($order->amount, 2) }}</td>
                            <td>
                                <span class="badge badge-secondary">{{ ucfirst($order->payment_method) }}</span>
                            </td>
                            <td>{{ $order->sender_number }}</td>
                            <td>{{ Str::limit($order->transaction_id, 15) }}</td>
                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                            <td>
                                @php
                                    $statusBadges = [
                                        'pending' => 'badge-warning',
                                        'verified' => 'badge-info',
                                        'delivered' => 'badge-success',
                                        'rejected' => 'badge-danger',
                                    ];
                                @endphp
                                <span class="badge {{ $statusBadges[$order->status] ?? 'badge-secondary' }}">{{ ucfirst($order->status) }}</span>
                            </td>
                            <td>
                                <div class="actions-cell">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline">View</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10">
                                <div class="empty-state">
                                    <div class="empty-state-icon">&#9993;</div>
                                    <h3>No orders found</h3>
                                    <p>Orders placed by customers will appear here.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($orders->hasPages())
            <div class="card-footer">
                {{ $orders->links('vendor.pagination.admin') }}
            </div>
        @endif
    </div>
@endsection
