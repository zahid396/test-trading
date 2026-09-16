@extends('admin.layouts.app')

@section('title', 'Categories')

@section('content')
    <div class="page-header">
        <h1>Categories</h1>
        <p>Organize your products into categories.</p>
    </div>

    <div class="card" style="margin-bottom:1.5rem;">
        <div class="card-header">
            <h3>Add New Category</h3>
        </div>
        <form method="POST" action="{{ route('admin.categories.store') }}">
            @csrf
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Name <span class="required">*</span></label>
                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required oninput="generateSlug(this)">
                        @error('name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="slug">Slug <span class="required">*</span></label>
                        <input type="text" id="slug" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug') }}" required>
                        @error('slug')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description') }}</textarea>
                    @error('description')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-check" style="margin-bottom:0;">
                    <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active') ? 'checked' : 'checked' }}>
                    <label for="is_active">Active</label>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Add Category</button>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Existing Categories ({{ $categories->count() }})</h3>
        </div>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Sort</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Description</th>
                        <th>Products</th>
                        <th>Active</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td>
                                <div class="actions-cell" style="white-space:nowrap;">
                                    <button class="btn btn-sm btn-outline" title="Move Up" onclick="reorderCategory(this, '{{ $category->id }}', -1)">&#8593;</button>
                                    <button class="btn btn-sm btn-outline" title="Move Down" onclick="reorderCategory(this, '{{ $category->id }}', 1)">&#8595;</button>
                                </div>
                            </td>
                            <td style="font-weight:500;">{{ $category->name }}</td>
                            <td>
                                <span class="badge badge-secondary">/{{ $category->slug }}</span>
                            </td>
                            <td>{{ Str::limit($category->description, 40) }}</td>
                            <td>{{ $category->products_count ?? $category->products->count() }}</td>
                            <td>
                                <label class="toggle">
                                    <input type="checkbox"
                                           {{ $category->is_active ? 'checked' : '' }}
                                           onchange="toggleActive(this)"
                                           data-url="{{ route('admin.categories.toggle', $category) }}">
                                    <span class="toggle-slider"></span>
                                </label>
                            </td>
                            <td>
                                <div class="actions-cell btn-group">
                                    <button class="btn btn-sm btn-outline" onclick="openEditModal('{{ $category->id }}')">Edit</button>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return deleteConfirm(this)">
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
                                    <div class="empty-state-icon">&#9776;</div>
                                    <h3>No categories yet</h3>
                                    <p>Create categories to organize your products.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @foreach($categories as $category)
        <div class="modal-overlay" id="editModal{{ $category->id }}">
            <div class="modal">
                <div class="modal-header">
                    <h3>Edit Category</h3>
                    <button class="modal-close" onclick="closeModal('editModal{{ $category->id }}')">&times;</button>
                </div>
                <form method="POST" action="{{ route('admin.categories.update', $category) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_name{{ $category->id }}">Name</label>
                            <input type="text" id="edit_name{{ $category->id }}" name="name" class="form-control" value="{{ $category->name }}" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_slug{{ $category->id }}">Slug</label>
                            <input type="text" id="edit_slug{{ $category->id }}" name="slug" class="form-control" value="{{ $category->slug }}" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_description{{ $category->id }}">Description</label>
                            <textarea id="edit_description{{ $category->id }}" name="description" class="form-control" rows="3">{{ $category->description }}</textarea>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" id="edit_active{{ $category->id }}" name="is_active" value="1" {{ $category->is_active ? 'checked' : '' }}>
                            <label for="edit_active{{ $category->id }}">Active</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline" onclick="closeModal('editModal{{ $category->id }}')">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
@endsection

@push('scripts')
<script>
    function generateSlug(input) {
        const slug = input.value
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');
        document.getElementById('slug').value = slug;
    }

    function openEditModal(id) {
        document.getElementById('editModal' + id).classList.add('active');
    }

    function reorderCategory(el, id, dir) {
        const row = el.closest('tr');
        if (dir === -1 && row.previousElementSibling) {
            row.parentNode.insertBefore(row, row.previousElementSibling);
        } else if (dir === 1 && row.nextElementSibling) {
            row.parentNode.insertBefore(row.nextElementSibling, row);
        }
        const rows = document.querySelectorAll('tbody tr');
        const items = Array.from(rows).map((r, i) => ({
            id: r.querySelector('button[onclick*="reorderCategory"]').getAttribute('onclick').match(/'(\d+)'/)[1],
            sort_order: i
        }));
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        fetch('{{ route("admin.categories.reorder") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ items })
        });
    }
</script>
@endpush
