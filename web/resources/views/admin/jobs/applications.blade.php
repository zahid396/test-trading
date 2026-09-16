@extends('admin.layouts.app')

@section('title', 'Job Applications')

@section('content')
    <div class="page-header">
        <h1>Job Applications</h1>
        <p>Review and manage applications from candidates.</p>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.jobs.applications') }}" class="filter-bar">
                <input type="text" name="search" class="form-control" placeholder="Search name, email, phone..." value="{{ request('search') }}" style="flex:1; min-width:180px;">
                <select name="job" class="form-control" style="min-width:180px;">
                    <option value="">All Jobs</option>
                    @foreach($jobs as $job)
                        <option value="{{ $job->id }}" {{ request('job') == $job->id ? 'selected' : '' }}>{{ Str::limit($job->title, 35) }}</option>
                    @endforeach
                </select>
                <select name="status" class="form-control" style="min-width:140px;">
                    <option value="">All Statuses</option>
                    @foreach(['pending','shortlisted','rejected','hired'] as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                @if(request('search') || request('job') || request('status'))
                    <a href="{{ route('admin.jobs.applications') }}" class="btn btn-outline btn-sm">Clear</a>
                @endif
            </form>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Job</th>
                            <th>Applicant</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Applied</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applications as $app)
                            <tr>
                                <td>
                                    <div style="font-weight:500; font-size:0.875rem;">{{ $app->jobPosting->title ?? '—' }}</div>
                                    <div style="font-size:0.75rem; color:var(--text-secondary);">{{ $app->jobPosting->company_name ?? '' }}</div>
                                </td>
                                <td>{{ $app->applicant_name }}</td>
                                <td><a href="mailto:{{ $app->applicant_email }}">{{ $app->applicant_email }}</a></td>
                                <td>{{ $app->phone ?: '—' }}</td>
                                <td>
                                    @php
                                        $statusBadges = [
                                            'pending' => 'badge-warning',
                                            'shortlisted' => 'badge-info',
                                            'rejected' => 'badge-danger',
                                            'hired' => 'badge-success',
                                        ];
                                    @endphp
                                    <form action="{{ route('admin.jobs.applications.status', $app) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" class="form-control" style="width:auto;padding:0.2rem 1.5rem 0.2rem 0.5rem;font-size:0.8125rem;border-radius:var(--radius);" onchange="this.form.submit()">
                                            @foreach(['pending','shortlisted','rejected','hired'] as $s)
                                                <option value="{{ $s }}" {{ $app->status == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                            @endforeach
                                        </select>
                                    </form>
                                </td>
                                <td style="white-space:nowrap; font-size:0.8125rem;">{{ $app->created_at->format('M j, Y g:i A') }}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline" onclick="openModal('appModal{{ $app->id }}')">View</button>
                                </td>
                            </tr>

                            <div class="modal-overlay" id="appModal{{ $app->id }}">
                                <div class="modal">
                                    <div class="modal-header">
                                        <h3>{{ $app->applicant_name }}</h3>
                                        <button class="modal-close" onclick="closeModal('appModal{{ $app->id }}')">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <p style="font-size:0.8125rem; color:var(--text-secondary); margin-bottom:0.25rem;"><strong>Email:</strong> {{ $app->applicant_email }}</p>
                                        <p style="font-size:0.8125rem; color:var(--text-secondary); margin-bottom:0.25rem;"><strong>Phone:</strong> {{ $app->phone ?: '—' }}</p>
                                        <p style="font-size:0.8125rem; color:var(--text-secondary); margin-bottom:0.75rem;"><strong>Applied:</strong> {{ $app->created_at->format('M j, Y g:i A') }}</p>
                                        <hr style="border:none; border-top:1px solid var(--border); margin:0.75rem 0;">
                                        <p style="font-size:0.8125rem; color:var(--text-secondary); margin-bottom:0.25rem;"><strong>Job:</strong> {{ $app->jobPosting->title ?? '—' }}</p>
                                        @if($app->cover_letter)
                                            <hr style="border:none; border-top:1px solid var(--border); margin:0.75rem 0;">
                                            <h4 style="font-size:0.8125rem; font-weight:600; margin-bottom:0.5rem;">Cover Letter</h4>
                                            <p style="font-size:0.875rem; color:var(--text-secondary); white-space:pre-line; line-height:1.7;">{{ $app->cover_letter }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <div class="empty-state-icon">&#9993;</div>
                                        <h3>No applications found</h3>
                                        <p>Applications will appear here as candidates apply.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($applications->hasPages())
            <div class="card-footer">
                {{ $applications->links('vendor.pagination.admin') }}
            </div>
        @endif
    </div>
@endsection