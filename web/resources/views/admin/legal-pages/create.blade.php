@extends('admin.layouts.app')

@section('title', 'Create Legal Page')

@section('content')
    <div class="page-header">
        <h1>Add New Legal Page</h1>
        <p>Create a new legal page for your store.</p>
    </div>

    <form method="POST" action="{{ route('admin.legal-pages.store') }}">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="title">Title <span class="required">*</span></label>
                        <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required oninput="generateSlug(this)">
                        @error('title')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="slug">Slug <span class="required">*</span></label>
                        <input type="text" id="slug" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug') }}" required>
                        @error('slug')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="form-group">
                    <label for="content">Content <span class="required">*</span></label>
                    <textarea id="content" name="content" class="form-control @error('content') is-invalid @enderror" rows="12" required>{{ old('content') }}</textarea>
                    @error('content')<div class="form-error">{{ $message }}</div>@enderror
                    <div class="form-text">Supports plain text content.</div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.legal-pages.index') }}" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-primary">Create Page</button>
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
</script>
@endpush
