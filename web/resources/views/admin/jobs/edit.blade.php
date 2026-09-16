@extends('admin.layouts.app')

@section('title', 'Edit Job Posting')

@section('content')
    <div class="page-header">
        <h1>Edit Job Posting</h1>
        <p>Update information for "{{ $job->title }}".</p>
    </div>

    <form method="POST" action="{{ route('admin.jobs.update', $job) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-body">
                <div class="form-section">
                    <h4 class="form-section-title">Basic Information</h4>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="title">Title <span class="required">*</span></label>
                            <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $job->title) }}" required oninput="generateSlug(this)">
                            @error('title')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="slug">Slug <span class="required">*</span></label>
                            <input type="text" id="slug" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $job->slug) }}" required>
                            @error('slug')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="subtitle">Short Description</label>
                        <input type="text" id="subtitle" name="subtitle" class="form-control @error('subtitle') is-invalid @enderror" value="{{ old('subtitle', $job->subtitle) }}">
                        @error('subtitle')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-section">
                    <h4 class="form-section-title">Job Details</h4>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="company_name">Company Name</label>
                            <input type="text" id="company_name" name="company_name" class="form-control @error('company_name') is-invalid @enderror" value="{{ old('company_name', $job->company_name) }}">
                            @error('company_name')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="location">Location</label>
                            <input type="text" id="location" name="location" class="form-control @error('location') is-invalid @enderror" value="{{ old('location', $job->location) }}">
                            @error('location')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="employment_type">Employment Type</label>
                            <select id="employment_type" name="employment_type" class="form-control">
                                <option value="">Select Type</option>
                                @foreach(['Full-time', 'Part-time', 'Contract', 'Internship', 'Remote'] as $type)
                                    <option value="{{ $type }}" {{ old('employment_type', $job->employment_type) == $type ? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                            @error('employment_type')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="salary_range">Salary Range</label>
                            <input type="text" id="salary_range" name="salary_range" class="form-control @error('salary_range') is-invalid @enderror" value="{{ old('salary_range', $job->salary_range) }}" placeholder="e.g. ৳30,000 - ৳50,000">
                            @error('salary_range')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="application_deadline">Application Deadline</label>
                            <input type="date" id="application_deadline" name="application_deadline" class="form-control @error('application_deadline') is-invalid @enderror" value="{{ old('application_deadline', optional($job->application_deadline)->format('Y-m-d')) }}">
                            @error('application_deadline')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h4 class="form-section-title">Image</h4>
                    @if($job->image)
                        <div style="margin-bottom:0.75rem;">
                            <img src="{{ asset('storage/jobs/' . $job->image) }}" class="thumb thumb-lg">
                        </div>
                    @endif
                    <div class="upload-area" onclick="document.getElementById('imageInput').click()">
                        <div class="upload-icon">&#128444;</div>
                        <p>Click to upload a new image</p>
                        <input type="file" id="imageInput" name="image" accept="image/*" class="form-control" onchange="previewImage(this)">
                    </div>
                    <div class="upload-preview">
                        <img id="imagePreview" style="display:none;">
                    </div>
                    @error('image')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-section">
                    <h4 class="form-section-title">Full Description</h4>
                    <div class="form-group">
                        <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" rows="6">{{ old('description', $job->description) }}</textarea>
                        @error('description')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-section">
                    <h4 class="form-section-title">Requirements</h4>
                    <div class="form-group">
                        <textarea id="requirements" name="requirements" class="form-control @error('requirements') is-invalid @enderror" rows="5" placeholder="One requirement per line">{{ old('requirements', $job->requirements) }}</textarea>
                        <div class="form-text">Enter one requirement per line.</div>
                        @error('requirements')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-section">
                    <h4 class="form-section-title">Status & Options</h4>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="sort_order">Sort Order</label>
                            <input type="number" id="sort_order" name="sort_order" min="0" class="form-control" value="{{ old('sort_order', $job->sort_order) }}">
                        </div>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $job->is_active) ? 'checked' : '' }}>
                        <label for="is_active">Active</label>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.jobs.index') }}" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Job</button>
            </div>
        </div>
    </form>
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

    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush