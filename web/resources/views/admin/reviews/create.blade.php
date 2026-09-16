@extends('admin.layouts.app')

@section('title', 'Create Review')

@section('content')
    <div class="page-header">
        <h1>Add New Review</h1>
        <p>Create a customer review.</p>
    </div>

    <form method="POST" action="{{ route('admin.reviews.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="form-section">
                    <h4 class="form-section-title">Review Details</h4>
                    <div class="form-group">
                        <label for="product_id">Product</label>
                        <select id="product_id" name="product_id" class="form-control">
                            <option value="">Select Product</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>{{ $product->title }}</option>
                            @endforeach
                        </select>
                        @error('product_id')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="customer_name">Customer Name <span class="required">*</span></label>
                        <input type="text" id="customer_name" name="customer_name" class="form-control @error('customer_name') is-invalid @enderror" value="{{ old('customer_name') }}" required>
                        @error('customer_name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="rating">Rating <span class="required">*</span></label>
                        <select id="rating" name="rating" class="form-control" required>
                            <option value="">Select Rating</option>
                            @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
                            @endfor
                        </select>
                        @error('rating')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="review_text">Review Text <span class="required">*</span></label>
                        <textarea id="review_text" name="review_text" class="form-control @error('review_text') is-invalid @enderror" rows="5" required>{{ old('review_text') }}</textarea>
                        @error('review_text')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-section">
                    <h4 class="form-section-title">Customer Avatar</h4>
                    <div class="upload-area" onclick="document.getElementById('avatarInput').click()">
                        <div class="upload-icon">&#128100;</div>
                        <p>Click to upload customer avatar</p>
                        <input type="file" id="avatarInput" name="customer_avatar" accept="image/*" class="form-control" onchange="previewImage(this)">
                    </div>
                    <div class="upload-preview">
                        <img id="imagePreview" style="display:none;">
                    </div>
                    @error('customer_avatar')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-section">
                    <h4 class="form-section-title">Options</h4>
                    <div style="display:flex; gap:2rem; flex-wrap:wrap;">
                        <div class="form-check">
                            <input type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                            <label for="is_featured">Featured</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active') ? 'checked' : 'checked' }}>
                            <label for="is_active">Active</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-primary">Create Review</button>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
<script>
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
