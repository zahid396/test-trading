@extends('admin.layouts.app')

@section('title', 'Legal Pages')

@section('content')
    <div class="page-header" style="display:flex; align-items:center; justify-content:space-between;">
        <div>
            <h1>Legal Pages</h1>
            <p>Manage legal pages like privacy policy, terms, refund policy, etc.</p>
        </div>
        <a href="{{ route('admin.legal-pages.create') }}" class="btn btn-primary">&#43; Add Page</a>
    </div>

    <div class="card">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Slug</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pages as $page)
                        <tr>
                            <td style="font-weight:500;">{{ $page->title }}</td>
                            <td>
                                <span class="badge badge-secondary">/{{ $page->slug }}</span>
                            </td>
                            <td>
                                <div class="actions-cell btn-group">
                                    <a href="{{ route('admin.legal-pages.edit', $page) }}" class="btn btn-sm btn-outline">Edit</a>
                                    <form action="{{ route('admin.legal-pages.destroy', $page) }}" method="POST" onsubmit="return deleteConfirm(this)">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">
                                <div class="empty-state">
                                    <div class="empty-state-icon">&#9998;</div>
                                    <h3>No legal pages yet</h3>
                                    <p>Create pages like privacy policy, terms of service, refund policy, etc.</p>
                                    <a href="{{ route('admin.legal-pages.create') }}" class="btn btn-primary btn-sm">Add Page</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
