@extends('admin.layouts.app')

@section('title', 'Job Postings')

@section('content')
    <div class="page-header" style="display:flex; align-items:center; justify-content:space-between;">
        <div>
            <h1>Job Postings</h1>
            <p>Manage all job listings on your site.</p>
        </div>
        <div style="display:flex;gap:.75rem;">
            <a href="{{ route('admin.jobs.applications') }}" class="btn btn-outline">Applications</a>
            <a href="{{ route('admin.jobs.create') }}" class="btn btn-primary">&#43; Add Job</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.jobs.index') }}" class="filter-bar">
                <input type="text" name="search" class="form-control" placeholder="Search by title, company, location..." value="{{ request('search') }}" style="flex:1; min-width:200px;">
                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                @if(request('search'))
                    <a href="{{ route('admin.jobs.index') }}" class="btn btn-outline btn-sm">Clear</a>
                @endif
            </form>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Title / Company</th>
                            <th>Type</th>
                            <th>Location</th>
                            <th>Deadline</th>
                            <th>Apps</th>
                            <th>Active</th>
                            <th>Sort</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jobs as $job)
                            <tr>
                                <td>
                                    @if($job->image)
                                        <img src="{{ asset('storage/jobs/' . $job->image) }}" class="thumb">
                                    @else
                                        <div class="thumb thumb-placeholder">&#9679;</div>
                                    @endif
                                </td>
                                <td>
                                    <div style="font-weight:500;">{{ $job->title }}</div>
                                    <div style="font-size:0.75rem; color:var(--text-secondary);">{{ $job->company_name }} · {{ $job->location }}</div>
                                </td>
                                <td><span class="badge badge-info">{{ $job->employment_type ?: '—' }}</span></td>
                                <td>{{ $job->location ?: '—' }}</td>
                                <td>{{ $job->application_deadline ? $job->application_deadline->format('M j, Y') : 'Rolling' }}</td>
                                <td>
                                    <a href="{{ route('admin.jobs.applications', ['job' => $job->id]) }}" style="color:var(--primary);font-weight:600;">{{ $job->applications_count ?? 0 }}</a>
                                </td>
                                <td>
                                    <label class="toggle">
                                        <input type="checkbox"
                                               {{ $job->is_active ? 'checked' : '' }}
                                               onchange="toggleActive(this)"
                                               data-url="{{ route('admin.jobs.toggle', $job) }}">
                                        <span class="toggle-slider"></span>
                                    </label>
                                </td>
                                <td>{{ $job->sort_order }}</td>
                                <td>
                                    <div class="actions-cell btn-group">
                                        <button class="btn btn-sm btn-outline" onclick="openModal('previewModal{{ $job->id }}')">View</button>
                                        <a href="{{ route('admin.jobs.edit', $job) }}" class="btn btn-sm btn-outline">Edit</a>
                                        <form action="{{ route('admin.jobs.destroy', $job) }}" method="POST" onsubmit="return deleteConfirm(this)">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <div class="modal-overlay" id="previewModal{{ $job->id }}">
                                <div class="modal">
                                    <div class="modal-header">
                                        <h3>{{ $job->title }}</h3>
                                        <button class="modal-close" onclick="closeModal('previewModal{{ $job->id }}')">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <div style="display:flex; gap:1rem; align-items:flex-start;">
                                            @if($job->image)
                                                <img src="{{ asset('storage/jobs/' . $job->image) }}" style="width:100px; height:100px; object-fit:cover; border-radius:var(--radius); border:1px solid var(--border);">
                                            @endif
                                            <div style="flex:1;">
                                                <h4 style="font-size:1rem; margin-bottom:0.25rem;">{{ $job->title }}</h4>
                                                <p style="font-size:0.8125rem; color:var(--text-secondary); margin-bottom:0.25rem;">{{ $job->company_name }}</p>
                                                <p style="font-size:0.8125rem; color:var(--text-secondary);">{{ $job->location }} · {{ $job->employment_type }} · {{ $job->salary_range ?: 'Not specified' }}</p>
                                            </div>
                                        </div>
                                        <hr style="border:none; border-top:1px solid var(--border); margin:1rem 0;">
                                        <p style="font-size:0.875rem; color:var(--text-secondary);">{{ $job->subtitle }}</p>
                                        <p style="font-size:0.875rem; color:var(--text-secondary); white-space:pre-line; margin-top:0.5rem;">{{ $job->description }}</p>
                                        @if($job->requirements)
                                            <hr style="border:none; border-top:1px solid var(--border); margin:1rem 0;">
                                            <h4 style="font-size:0.8125rem; font-weight:600; margin-bottom:0.5rem;">Requirements</h4>
                                            <ul style="font-size:0.8125rem; color:var(--text-secondary); padding-left:1.25rem;">
                                                @foreach(explode("\n", $job->requirements) as $line)
                                                    @if(trim($line) !== '')
                                                        <li>{{ $line }}</li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <a href="{{ route('admin.jobs.edit', $job) }}" class="btn btn-primary btn-sm">Edit</a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="9">
                                    <div class="empty-state">
                                        <div class="empty-state-icon">&#9733;</div>
                                        <h3>No job postings found</h3>
                                        <p>Start by adding your first job listing.</p>
                                        <a href="{{ route('admin.jobs.create') }}" class="btn btn-primary btn-sm">Add Job</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($jobs->hasPages())
            <div class="card-footer">
                {{ $jobs->links('vendor.pagination.admin') }}
            </div>
        @endif
    </div>
@endsection