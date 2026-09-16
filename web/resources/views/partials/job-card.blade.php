@props(['job'])
@php
    $open = $job->isOpen();
@endphp
<div class="jcard" onclick="window.location='{{ route('jobs.show', $job->slug) }}'">
    <div class="jcard-img">
        @if($job->image)
            <img src="{{ asset('storage/jobs/' . $job->image) }}" alt="{{ $job->title }}" loading="lazy">
        @else
            <div class="jcard-placeholder">
                <svg width="48" height="48" fill="none" stroke="#9ca3af" stroke-width="1.5"><circle cx="12" cy="7" r="4"/><path d="M4 21v-2a8 8 0 0 1 16 0v2"/><path d="M10 12l2-2 2 2"/></svg>
            </div>
        @endif
        @if($job->employment_type)
            <span class="jcard-badge">{{ $job->employment_type }}</span>
        @endif
        @if(!$open)
            <span class="jcard-closed">Closed</span>
        @endif
    </div>
    <div class="jcard-body">
        <h3 class="jcard-title">{{ $job->title }}</h3>
        @if($job->company_name)
            <p class="jcard-company">{{ $job->company_name }}</p>
        @endif
        @if($job->subtitle)
            <p class="jcard-subtitle">{{ Str::limit($job->subtitle, 70) }}</p>
        @endif
        <div class="jcard-meta">
            @if($job->location)
                <span>📍 {{ $job->location }}</span>
            @endif
            @if($job->salary_range)
                <span>৳ {{ $job->salary_range }}</span>
            @endif
        </div>
        <div class="jcard-deadline">
            @if($job->application_deadline)
                Deadline: <strong>{{ $job->application_deadline->format('M j, Y') }}</strong>
            @else
                Rolling applications
            @endif
        </div>
        <button class="jcard-btn">{{ $open ? 'View Details & Apply' : 'View Details' }}</button>
    </div>
</div>