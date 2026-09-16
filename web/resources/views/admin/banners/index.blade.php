@extends('admin.layouts.app')

@section('title', 'Banners')

@section('content')
    <div class="page-header" style="display:flex; align-items:center; justify-content:space-between;">
        <div>
            <h1>Banners</h1>
            <p>Manage homepage banner slides.</p>
        </div>
        <a href="{{ route('admin.banners.create') }}" class="btn btn-primary">&#43; Add Banner</a>
    </div>

    <div class="card">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Action Type</th>
                        <th>Link Destination</th>
                        <th>Active</th>
                        <th>Sort</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($banners as $banner)
                        <tr>
                            <td>
                                @if($banner->isVideo())
                                    <img src="https://i.ytimg.com/vi/{{ $banner->youtubeId() }}/hqdefault.jpg" class="thumb" alt="">
                                @elseif($banner->image)
                                    <img src="{{ asset('storage/banners/' . $banner->image) }}" class="thumb" alt="">
                                @else
                                    <div class="thumb thumb-placeholder">&#9654;</div>
                                @endif
                                <div style="margin-top:0.25rem;">
                                    <span class="badge badge-primary" style="font-size:0.65rem;">{{ $banner->isVideo() ? 'VIDEO' : 'IMAGE' }}</span>
                                </div>
                            </td>
                            <td>
                                <div style="font-weight:500;">{{ $banner->title }}</div>
                                <div style="font-size:0.75rem; color:var(--text-secondary);">{{ Str::limit($banner->subtitle, 40) }}</div>
                            </td>
                            <td>
                                @php
                                    $actionTypes = [
                                        'none' => 'None',
                                        'product' => 'Open Specific Product',
                                        'product_page' => 'Product Details Page',
                                        'external_url' => 'External URL',
                                    ];
                                @endphp
                                <span class="badge badge-primary">{{ $actionTypes[$banner->action_type] ?? $banner->action_type }}</span>
                            </td>
                            <td>
                                @if($banner->action_type == 'product' && $banner->actionProduct)
                                    <a href="{{ route('products.show', $banner->actionProduct->slug) }}" target="_blank">{{ $banner->actionProduct->title }}</a>
                                @elseif($banner->action_type == 'product_page' && $banner->action_product_id)
                                    Product #{{ $banner->action_product_id }}
                                @elseif($banner->action_type == 'external_url')
                                    <a href="{{ $banner->action_url }}" target="_blank">{{ Str::limit($banner->action_url, 40) }}</a>
                                @else
                                    <span style="color:var(--text-secondary);">-</span>
                                @endif
                            </td>
                            <td>
                                <label class="toggle">
                                    <input type="checkbox"
                                           {{ $banner->is_active ? 'checked' : '' }}
                                           onchange="toggleActive(this)"
                                           data-url="{{ route('admin.banners.toggle', $banner) }}">
                                    <span class="toggle-slider"></span>
                                </label>
                            </td>
                            <td>{{ $banner->sort_order }}</td>
                            <td>
                                <div class="actions-cell btn-group">
                                    <a href="{{ route('admin.banners.edit', $banner) }}" class="btn btn-sm btn-outline">Edit</a>
                                    <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" onsubmit="return deleteConfirm(this)">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <div class="empty-state-icon">&#9654;</div>
                                    <h3>No banners found</h3>
                                    <p>Create your first homepage banner.</p>
                                    <a href="{{ route('admin.banners.create') }}" class="btn btn-primary btn-sm">Add Banner</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
