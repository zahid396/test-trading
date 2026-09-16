@extends('admin.layouts.app')

@section('title', 'Edit Product')

@section('content')
    <div class="page-header">
        <h1>Edit Product</h1>
        <p>Update information for "{{ $product->title }}".</p>
    </div>

    <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-body">
                <div class="form-section">
                    <h4 class="form-section-title">Basic Information</h4>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="title">Title <span class="required">*</span></label>
                            <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $product->title) }}" required oninput="generateSlug(this)">
                            @error('title')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="slug">Slug <span class="required">*</span></label>
                            <input type="text" id="slug" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $product->slug) }}" required>
                            @error('slug')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="subtitle">Subtitle</label>
                        <input type="text" id="subtitle" name="subtitle" class="form-control @error('subtitle') is-invalid @enderror" value="{{ old('subtitle', $product->subtitle) }}">
                        @error('subtitle')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="category_id">Category</label>
                        <select id="category_id" name="category_id" class="form-control">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-section">
                    <h4 class="form-section-title">Pricing</h4>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="price">Price <span class="required">*</span></label>
                            <input type="number" id="price" name="price" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $product->price) }}" required>
                            @error('price')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="old_price">Old Price</label>
                            <input type="number" id="old_price" name="old_price" step="0.01" min="0" class="form-control @error('old_price') is-invalid @enderror" value="{{ old('old_price', $product->old_price) }}">
                            @error('old_price')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="discount">Discount (%)</label>
                            <input type="number" id="discount" name="discount" step="0.01" min="0" max="100" class="form-control @error('discount') is-invalid @enderror" value="{{ old('discount', $product->discount) }}">
                            @error('discount')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h4 class="form-section-title">Image</h4>
                    @if($product->image)
                        <div style="margin-bottom:0.75rem;">
                            <img src="{{ asset('storage/products/' . $product->image) }}" class="thumb thumb-lg">
                        </div>
                    @endif
                    <div class="upload-area" onclick="document.getElementById('imageInput').click()">
                        <div class="upload-icon">&#128444;</div>
                        <p>Click to upload a new product image</p>
                        <input type="file" id="imageInput" name="image" accept="image/*" class="form-control" onchange="previewImage(this)">
                    </div>
                    <div class="upload-preview">
                        <img id="imagePreview" style="display:none;">
                    </div>
                    @error('image')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-section">
                    <h4 class="form-section-title">Description</h4>
                    <div class="form-group">
                        <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" rows="5">{{ old('description', $product->description) }}</textarea>
                        @error('description')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-section">
                    <h4 class="form-section-title">Features</h4>
                    <div id="featuresContainer">
                        @foreach(old('features', $product->features ?? []) as $index => $feature)
                            <div class="form-group feature-row">
                                <div style="display:flex; gap:0.5rem;">
                                    <input type="text" name="features[]" class="form-control" placeholder="Feature {{ $index + 1 }}" value="{{ $feature }}">
                                    <button type="button" class="btn btn-danger btn-sm" onclick="removeFeatureRow(this)">&times;</button>
                                </div>
                            </div>
                        @endforeach
                        @if(empty(old('features', $product->features ?? [])))
                            <div class="form-group feature-row">
                                <div style="display:flex; gap:0.5rem;">
                                    <input type="text" name="features[]" class="form-control" placeholder="Feature 1">
                                    <button type="button" class="btn btn-danger btn-sm" onclick="removeFeatureRow(this)">&times;</button>
                                </div>
                            </div>
                        @endif
                    </div>
                    <button type="button" class="btn btn-outline btn-sm" onclick="addFeatureRow()">&#43; Add Feature</button>
                </div>

                <div class="form-section">
                    <h4 class="form-section-title">Digital Info</h4>
                    <div class="form-group">
                        <textarea id="digital_info" name="digital_info" class="form-control @error('digital_info') is-invalid @enderror" rows="4" placeholder="Delivery instructions, redeeming info, license details, etc.">{{ old('digital_info', $product->digital_info) }}</textarea>
                        @error('digital_info')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-section">
                    <h4 class="form-section-title">Status & Options</h4>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select id="status" name="status" class="form-control">
                                <option value="available" {{ old('status', $product->status) == 'available' ? 'selected' : '' }}>Available</option>
                                <option value="coming_soon" {{ old('status', $product->status) == 'coming_soon' ? 'selected' : '' }}>Coming Soon</option>
                                <option value="sold_out" {{ old('status', $product->status) == 'sold_out' ? 'selected' : '' }}>Sold Out</option>
                                <option value="hidden" {{ old('status', $product->status) == 'hidden' ? 'selected' : '' }}>Hidden</option>
                            </select>
                            @error('status')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="sort_order">Sort Order</label>
                            <input type="number" id="sort_order" name="sort_order" min="0" class="form-control" value="{{ old('sort_order', $product->sort_order) }}">
                        </div>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                        <label for="is_active">Active</label>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Product</button>
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

    function addFeatureRow() {
        const container = document.getElementById('featuresContainer');
        const div = document.createElement('div');
        div.className = 'form-group feature-row';
        div.innerHTML = `
            <div style="display:flex; gap:0.5rem;">
                <input type="text" name="features[]" class="form-control" placeholder="Feature ${container.children.length + 1}">
                <button type="button" class="btn btn-danger btn-sm" onclick="removeFeatureRow(this)">&times;</button>
            </div>`;
        container.appendChild(div);
    }

    function removeFeatureRow(btn) {
        const container = document.getElementById('featuresContainer');
        if (container.children.length > 1) {
            btn.closest('.feature-row').remove();
            const rows = container.querySelectorAll('input');
            rows.forEach((input, i) => input.placeholder = `Feature ${i + 1}`);
        }
    }
</script>
@endpush
