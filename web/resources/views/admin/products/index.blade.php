@extends('admin.layouts.app')

@section('title', 'Products')

@section('content')
    <div class="page-header" style="display:flex; align-items:center; justify-content:space-between;">
        <div>
            <h1>Products</h1>
            <p>Manage all your digital products.</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">&#43; Add Product</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.products.index') }}" class="filter-bar">
                <input type="text" name="search" class="form-control" placeholder="Search by title or subtitle..." value="{{ request('search') }}" style="flex:1; min-width:200px;">
                <select name="status" class="form-control">
                    <option value="">All Statuses</option>
                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="coming_soon" {{ request('status') == 'coming_soon' ? 'selected' : '' }}>Coming Soon</option>
                    <option value="sold_out" {{ request('status') == 'sold_out' ? 'selected' : '' }}>Sold Out</option>
                    <option value="hidden" {{ request('status') == 'hidden' ? 'selected' : '' }}>Hidden</option>
                </select>
                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                @if(request('search') || request('status'))
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline btn-sm">Clear</a>
                @endif
            </form>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Active</th>
                            <th>Sort</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>
                                    @if($product->image)
                                        <img src="{{ asset('storage/products/' . $product->image) }}" class="thumb">
                                    @else
                                        <div class="thumb thumb-placeholder">&#9679;</div>
                                    @endif
                                </td>
                                <td>
                                    <div style="font-weight:500;">{{ $product->title }}</div>
                                    <div style="font-size:0.75rem; color:var(--text-secondary);">{{ Str::limit($product->subtitle, 40) }}</div>
                                </td>
                                <td>
                                    <div>{{ number_format($product->price, 2) }}</div>
                                    @if($product->old_price && $product->old_price > $product->price)
                                        <div style="font-size:0.75rem; color:var(--text-secondary); text-decoration:line-through;">
                                            {{ number_format($product->old_price, 2) }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $statusBadges = [
                                            'available' => 'badge-success',
                                            'coming_soon' => 'badge-info',
                                            'sold_out' => 'badge-warning',
                                            'hidden' => 'badge-secondary',
                                        ];
                                    @endphp
                                    <span class="badge {{ $statusBadges[$product->status] ?? 'badge-secondary' }}">
                                        {{ ucwords(str_replace('_', ' ', $product->status)) }}
                                    </span>
                                </td>
                                <td>
                                    <label class="toggle">
                                        <input type="checkbox"
                                               {{ $product->is_active ? 'checked' : '' }}
                                               onchange="toggleActive(this)"
                                               data-url="{{ route('admin.products.toggle', $product) }}">
                                        <span class="toggle-slider"></span>
                                    </label>
                                </td>
                                <td>{{ $product->sort_order }}</td>
                                <td>
                                    <div class="actions-cell btn-group">
                                        <button class="btn btn-sm btn-outline" onclick="openModal('previewModal{{ $product->id }}')">View</button>
                                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline">Edit</a>
                                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return deleteConfirm(this)">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <div class="modal-overlay" id="previewModal{{ $product->id }}">
                                <div class="modal">
                                    <div class="modal-header">
                                        <h3>{{ $product->title }}</h3>
                                        <button class="modal-close" onclick="closeModal('previewModal{{ $product->id }}')">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <div style="display:flex; gap:1rem; align-items:flex-start;">
                                            @if($product->image)
                                                <img src="{{ asset('storage/products/' . $product->image) }}" style="width:100px; height:100px; object-fit:cover; border-radius:var(--radius); border:1px solid var(--border);">
                                            @endif
                                            <div style="flex:1;">
                                                <h4 style="font-size:1rem; margin-bottom:0.5rem;">{{ $product->title }}</h4>
                                                <p style="font-size:0.8125rem; color:var(--text-secondary); margin-bottom:0.5rem;">{{ $product->subtitle }}</p>
                                                <span class="badge {{ $statusBadges[$product->status] ?? 'badge-secondary' }}">{{ ucwords(str_replace('_', ' ', $product->status)) }}</span>
                                            </div>
                                        </div>
                                        <hr style="border:none; border-top:1px solid var(--border); margin:1rem 0;">
                                        <p style="font-size:0.875rem; color:var(--text-secondary);">{{ $product->description }}</p>
                                        @if($product->features)
                                            <hr style="border:none; border-top:1px solid var(--border); margin:1rem 0;">
                                            <h4 style="font-size:0.8125rem; font-weight:600; margin-bottom:0.5rem;">Features</h4>
                                            <ul style="font-size:0.8125rem; color:var(--text-secondary); padding-left:1.25rem;">
                                                @foreach($product->features as $feature)
                                                    <li>{{ $feature }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary btn-sm">Edit</a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <div class="empty-state-icon">&#9733;</div>
                                        <h3>No products found</h3>
                                        <p>Start by adding your first digital product.</p>
                                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">Add Product</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($products->hasPages())
            <div class="card-footer">
                {{ $products->links('vendor.pagination.admin') }}
            </div>
        @endif
    </div>
@endsection
