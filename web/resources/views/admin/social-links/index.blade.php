@extends('admin.layouts.app')

@section('title', 'Social Links')

@section('content')
    <div class="page-header">
        <h1>Social Links</h1>
        <p>Manage your social media and contact links.</p>
    </div>

    <div class="card" style="margin-bottom:1.5rem;">
        <div class="card-header">
            <h3>Add New Social Link</h3>
        </div>
        <form method="POST" action="{{ route('admin.social-links.store') }}">
            @csrf
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="platform">Platform</label>
                        <select id="platform" name="platform" class="form-control" required>
                            <option value="">Select Platform</option>
                            @foreach(['facebook', 'whatsapp', 'messenger', 'telegram', 'instagram', 'tiktok', 'youtube', 'twitter', 'linkedin', 'email'] as $p)
                                <option value="{{ $p }}" {{ old('platform') == $p ? 'selected' : '' }}>{{ ucfirst($p === 'twitter' ? 'X (Twitter)' : $p) }}</option>
                            @endforeach
                        </select>
                        @error('platform')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="label">Label</label>
                        <input type="text" id="label" name="label" class="form-control @error('label') is-invalid @enderror" value="{{ old('label') }}" placeholder="e.g. Facebook Page">
                        @error('label')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="url">URL</label>
                        <input type="url" id="url" name="url" class="form-control @error('url') is-invalid @enderror" value="{{ old('url') }}" placeholder="https://...">
                        @error('url')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="form-check" style="margin-bottom:0;">
                    <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active') ? 'checked' : 'checked' }}>
                    <label for="is_active">Active</label>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Add Link</button>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Existing Links ({{ $socialLinks->count() }})</h3>
        </div>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Sort</th>
                        <th>Platform</th>
                        <th>Label</th>
                        <th>URL</th>
                        <th>Active</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($socialLinks as $link)
                        <tr>
                            <td>
                                <div class="actions-cell" style="white-space:nowrap;">
                                    <button class="btn btn-sm btn-outline" title="Move Up" onclick="reorderSocial(this, '{{ $link->id }}', -1)">&#8593;</button>
                                    <button class="btn btn-sm btn-outline" title="Move Down" onclick="reorderSocial(this, '{{ $link->id }}', 1)">&#8595;</button>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-primary">{{ $link->platform }}</span>
                            </td>
                            <td style="font-weight:500;">{{ $link->label ?? '-' }}</td>
                            <td>
                                @if($link->url)
                                    <a href="{{ $link->url }}" target="_blank" style="font-size:0.8125rem;">{{ Str::limit($link->url, 40) }}</a>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <label class="toggle">
                                    <input type="checkbox"
                                           {{ $link->is_active ? 'checked' : '' }}
                                           onchange="toggleActive(this)"
                                           data-url="{{ route('admin.social-links.toggle', $link) }}">
                                    <span class="toggle-slider"></span>
                                </label>
                            </td>
                            <td>
                                <div class="actions-cell btn-group">
                                    <button class="btn btn-sm btn-outline" onclick="openEditModal('{{ $link->id }}')">Edit</button>
                                    <form action="{{ route('admin.social-links.destroy', $link) }}" method="POST" onsubmit="return deleteConfirm(this)">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <div class="empty-state-icon">&#9742;</div>
                                    <h3>No social links</h3>
                                    <p>Add your social media links using the form above.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @foreach($socialLinks as $link)
        <div class="modal-overlay" id="editModal{{ $link->id }}">
            <div class="modal">
                <div class="modal-header">
                    <h3>Edit Social Link</h3>
                    <button class="modal-close" onclick="closeModal('editModal{{ $link->id }}')">&times;</button>
                </div>
                <form method="POST" action="{{ route('admin.social-links.update', $link) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_platform{{ $link->id }}">Platform</label>
                            <select id="edit_platform{{ $link->id }}" name="platform" class="form-control" required>
                                @foreach(['facebook', 'whatsapp', 'messenger', 'telegram', 'instagram', 'tiktok', 'youtube', 'twitter', 'linkedin', 'email'] as $p)
                                    <option value="{{ $p }}" {{ $link->platform == $p ? 'selected' : '' }}>{{ ucfirst($p === 'twitter' ? 'X (Twitter)' : $p) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_label{{ $link->id }}">Label</label>
                            <input type="text" id="edit_label{{ $link->id }}" name="label" class="form-control" value="{{ $link->label }}">
                        </div>
                        <div class="form-group">
                            <label for="edit_url{{ $link->id }}">URL</label>
                            <input type="url" id="edit_url{{ $link->id }}" name="url" class="form-control" value="{{ $link->url }}">
                        </div>
                        <div class="form-check">
                            <input type="checkbox" id="edit_active{{ $link->id }}" name="is_active" value="1" {{ $link->is_active ? 'checked' : '' }}>
                            <label for="edit_active{{ $link->id }}">Active</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline" onclick="closeModal('editModal{{ $link->id }}')">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
@endsection

@push('scripts')
<script>
    function openEditModal(id) {
        document.getElementById('editModal' + id).classList.add('active');
    }

    function reorderSocial(el, id, dir) {
        const row = el.closest('tr');
        if (dir === -1 && row.previousElementSibling) {
            row.parentNode.insertBefore(row, row.previousElementSibling);
        } else if (dir === 1 && row.nextElementSibling) {
            row.parentNode.insertBefore(row.nextElementSibling, row);
        }
        const rows = document.querySelectorAll('tbody tr');
        const items = Array.from(rows).map((r, i) => ({
            id: r.querySelector('button[onclick*="reorderSocial"]').getAttribute('onclick').match(/'(\d+)'/)[1],
            sort_order: i
        }));
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        fetch('{{ route("admin.social-links.reorder") }}', {
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
