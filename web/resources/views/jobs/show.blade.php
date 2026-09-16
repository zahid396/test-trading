@extends('layouts.app')

@section('content')
<div class="container" style="padding: 2.5rem 1rem 4rem;">
    <a href="{{ route('jobs.index') }}" style="display:inline-flex;align-items:center;gap:.4rem;color:var(--primary);font-weight:600;font-size:.9rem;margin-bottom:1.5rem;">← Back to Jobs</a>

    @if(session('success'))
        <div style="padding:1rem 1.25rem;border-radius:var(--radius);background:rgba(22,199,132,.12);border:1px solid rgba(22,199,132,.3);color:var(--accent);margin-bottom:1.5rem;font-weight:600;font-size:.9rem;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="padding:1rem 1.25rem;border-radius:var(--radius);background:rgba(246,70,93,.12);border:1px solid rgba(246,70,93,.3);color:var(--down);margin-bottom:1.5rem;font-weight:600;font-size:.9rem;">
            {{ session('error') }}
        </div>
    @endif

    <div class="job-detail-card">
        <div class="job-detail-hero">
            <div class="job-detail-logo">
                @if($job->image)
                    <img src="{{ asset('storage/jobs/' . $job->image) }}" alt="{{ $job->title }}">
                @else
                    <svg width="28" height="28" fill="none" stroke="#9ca3af" stroke-width="1.5"><circle cx="14" cy="8" r="4"/><path d="M4 28v-4a10 10 0 0 1 20 0v4"/></svg>
                @endif
            </div>
            <div style="flex:1;">
                <div class="job-detail-title">{{ $job->title }}</div>
                @if($job->company_name)
                    <div class="job-detail-company">{{ $job->company_name }}</div>
                @endif
                <div style="display:flex;flex-wrap:wrap;gap:.5rem;margin-top:.75rem;">
                    @if($job->employment_type)
                        <span class="job-detail-chip"><span class="dot"></span>{{ $job->employment_type }}</span>
                    @endif
                    @if($job->location)
                        <span class="job-detail-chip">📍 {{ $job->location }}</span>
                    @endif
                    @if($job->salary_range)
                        <span class="job-detail-chip">৳ {{ $job->salary_range }}</span>
                    @endif
                    @if($job->application_deadline)
                        <span class="job-detail-chip">⏰ Deadline: {{ $job->application_deadline->format('M j, Y') }}</span>
                    @endif
                </div>
            </div>
            <div class="job-detail-deadline">
                <span>Status</span>
                <b style="margin-top:.2rem;display:inline-flex;align-items:center;gap:.4rem;">
                    @if($job->isOpen())
                        <span style="width:8px;height:8px;border-radius:50%;background:var(--accent);display:inline-block;"></span> Open
                    @else
                        <span style="width:8px;height:8px;border-radius:50%;background:var(--down);display:inline-block;"></span> Closed
                    @endif
                </b>
            </div>
        </div>

        <div class="job-detail-body">
            @if($job->subtitle)
                <p style="font-size:1.05rem;color:var(--gray-800);margin-bottom:1.25rem;">{{ $job->subtitle }}</p>
            @endif

            @if($job->description)
                <h3>Description</h3>
                <p>{{ $job->description }}</p>
            @endif

            @if($job->requirements)
                <h3>Requirements</h3>
                <ul>
                    @foreach(explode("\n", $job->requirements) as $line)
                        @if(trim($line) !== '')
                            <li>{{ $line }}</li>
                        @endif
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    @if($job->isOpen())
        <div class="apply-box">
            <h3>Apply for this Position</h3>
            <p class="apply-hint">Fill in your details below. We'll reach out to you via email.</p>
            <form method="POST" action="{{ route('jobs.apply', $job) }}">
                @csrf
                <div class="apply-field {{ $errors->has('applicant_name') ? 'is-invalid' : '' }}">
                    <label for="applicant_name">Full Name <span style="color:var(--down);">*</span></label>
                    <input type="text" id="applicant_name" name="applicant_name" value="{{ old('applicant_name') }}" placeholder="Your full name" required>
                    @error('applicant_name')<div class="apply-error">{{ $message }}</div>@enderror
                </div>
                <div class="apply-field {{ $errors->has('applicant_email') ? 'is-invalid' : '' }}">
                    <label for="applicant_email">Email Address <span style="color:var(--down);">*</span></label>
                    <input type="email" id="applicant_email" name="applicant_email" value="{{ old('applicant_email') }}" placeholder="you@example.com" required>
                    @error('applicant_email')<div class="apply-error">{{ $message }}</div>@enderror
                </div>
                <div class="apply-field {{ $errors->has('phone') ? 'is-invalid' : '' }}">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" placeholder="+880 1XXXXXXXXX">
                    @error('phone')<div class="apply-error">{{ $message }}</div>@enderror
                </div>
                <div class="apply-field {{ $errors->has('cover_letter') ? 'is-invalid' : '' }}">
                    <label for="cover_letter">Cover Letter / Message</label>
                    <textarea id="cover_letter" name="cover_letter" rows="5" placeholder="Tell us why you're a good fit for this position...">{{ old('cover_letter') }}</textarea>
                    @error('cover_letter')<div class="apply-error">{{ $message }}</div>@enderror
                </div>
                <button type="submit" class="apply-submit">Submit Application</button>
            </form>
        </div>
    @else
        <div class="job-closed-note">Applications for this position are currently closed.</div>
    @endif

    @if(isset($related) && $related->count())
        <div style="margin-top:3rem;">
            <h2 style="font-size:1.3rem;font-weight:800;color:var(--gray-900);margin-bottom:1.25rem;">Related Jobs</h2>
            <div class="jcard-grid">
                @foreach($related as $rjob)
                    @include('partials.job-card', ['job' => $rjob])
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection