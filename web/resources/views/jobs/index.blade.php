@extends('layouts.app')

@section('content')
<div class="jobs-page">
    <div class="container">
        <div class="jobs-header">
            <h1>Job Circulars</h1>
            <p>Find your next career opportunity and apply with your email</p>
        </div>

        <form method="GET" action="{{ route('jobs.index') }}">
            <div class="jobs-toolbar">
                <input type="text" name="search" class="jt-input" placeholder="Search jobs, companies, locations..." value="{{ request('search') }}">
                <select name="type" class="jt-input" onchange="this.form.submit()">
                    <option value="">All Job Types</option>
                    @foreach($types as $type)
                        <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
                <noscript><button type="submit" class="jt-btn">Search</button></noscript>
            </div>
        </form>

        @if($jobs->count())
            <div class="jcard-grid">
                @foreach($jobs as $job)
                    @include('partials.job-card', ['job' => $job])
                @endforeach
            </div>
            @if($jobs->hasPages())
                <div class="pagination">{{ $jobs->withQueryString()->links('vendor.pagination.site') }}</div>
            @endif
        @else
            <div class="empty-state">
                <svg width="64" height="64" fill="none" stroke="#9ca3af" stroke-width="1.5"><circle cx="12" cy="7" r="4"/><path d="M4 21v-2a8 8 0 0 1 16 0v2"/><path d="M10 12l2-2 2 2"/></svg>
                <h3>No jobs found</h3>
                <p>Try adjusting your search or filter criteria.</p>
            </div>
        @endif
    </div>
</div>
@endsection