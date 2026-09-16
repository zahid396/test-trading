@extends('admin.layouts.app')

@section('title', 'Edit Banner')

@section('content')
    <div class="page-header">
        <h1>Edit Banner</h1>
        <p>Update the banner "{{ $banner->title }}".</p>
    </div>

    <form method="POST" action="{{ route('admin.banners.update', $banner) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-body">
                <div class="form-section">
                    <h4 class="form-section-title">Banner Text <span style="font-weight:400; font-size:0.75rem; color:var(--text-secondary);">(optional)</span></h4>
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $banner->title) }}" placeholder="Optional - shown over the banner">
                        @error('title')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="subtitle">Subtitle</label>
                        <input type="text" id="subtitle" name="subtitle" class="form-control @error('subtitle') is-invalid @enderror" value="{{ old('subtitle', $banner->subtitle) }}" placeholder="Optional - shown under the title">
                        @error('subtitle')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="button_text">Button Text</label>
                        <input type="text" id="button_text" name="button_text" class="form-control @error('button_text') is-invalid @enderror" value="{{ old('button_text', $banner->button_text) }}" placeholder="Optional - e.g. Buy Now">
                        @error('button_text')<div class="form-error">{{ $message }}</div>@enderror
                        <div style="font-size:0.75rem; color:var(--text-secondary); margin-top:0.375rem;">Banners look great with or without text. The whole image is clickable when a target is set.</div>
                    </div>
                </div>

                <div class="form-section">
                    <h4 class="form-section-title">Banner Media</h4>
                    <div class="form-group">
                        <label>Media Type</label>
                        <div style="display:flex; flex-wrap:wrap; gap:1.5rem; margin-top:0.375rem;">
                            <label class="form-check">
                                <input type="radio" name="media_type" value="image" {{ old('media_type', $banner->media_type ?? 'image') == 'image' ? 'checked' : '' }} onchange="toggleMediaField()">
                                <span>Uploaded Image</span>
                            </label>
                            <label class="form-check">
                                <input type="radio" name="media_type" value="youtube" {{ old('media_type', $banner->media_type) == 'youtube' ? 'checked' : '' }} onchange="toggleMediaField()">
                                <span>YouTube Video</span>
                            </label>
                        </div>
                        @error('media_type')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div id="imageMediaBlock">
                        @if($banner->image)
                            <div style="margin-bottom:0.75rem;">
                                <img src="{{ asset('storage/banners/' . $banner->image) }}" style="max-width:300px; border-radius:var(--radius); border:1px solid var(--border);">
                            </div>
                        @endif
                        <div class="upload-area" onclick="document.getElementById('imageInput').click()">
                            <div class="upload-icon">&#128444;</div>
                            <p>Click to upload a new banner image</p>
                            <input type="file" id="imageInput" name="image" accept="image/*" class="form-control" onchange="previewImage(this)">
                        </div>
                        <div class="upload-preview">
                            <img id="imagePreview" style="display:none;">
                        </div>
                        @error('image')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div id="videoMediaBlock" style="display:none;">
                        @if($banner->isVideo())
                            <div style="margin-bottom:0.75rem;">
                                <img src="https://i.ytimg.com/vi/{{ $banner->youtubeId() }}/hqdefault.jpg" style="max-width:300px; border-radius:var(--radius); border:1px solid var(--border);">
                            </div>
                        @endif
                        <div class="form-group">
                            <label for="video_url">YouTube Video URL</label>
                            <input type="url" id="video_url" name="video_url" class="form-control @error('video_url') is-invalid @enderror" value="{{ old('video_url', $banner->video_url) }}" placeholder="https://www.youtube.com/watch?v=VIDEO_ID">
                            @error('video_url')<div class="form-error">{{ $message }}</div>@enderror
                            <div style="font-size:0.75rem; color:var(--text-secondary); margin-top:0.375rem;">Visitors click the thumbnail to play the video directly on your website. The video must be public and allow embedding on other sites.</div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h4 class="form-section-title">Click Action <span style="font-weight:400; font-size:0.75rem; color:var(--text-secondary);">(optional)</span></h4>
                    <div class="form-group">
                        <label>Action Type</label>
                        <div style="display:flex; flex-wrap:wrap; gap:1.5rem; margin-top:0.375rem;">
                            <label class="form-check">
                                <input type="radio" name="action_type" value="none" {{ old('action_type', $banner->action_type) == 'none' ? 'checked' : '' }} onchange="toggleActionFields()">
                                <span>None</span>
                            </label>
                            <label class="form-check">
                                <input type="radio" name="action_type" value="product" {{ old('action_type', $banner->action_type) == 'product' ? 'checked' : '' }} onchange="toggleActionFields()">
                                <span>Open Specific Product</span>
                            </label>
                            <label class="form-check">
                                <input type="radio" name="action_type" value="product_page" {{ old('action_type', $banner->action_type) == 'product_page' ? 'checked' : '' }} onchange="toggleActionFields()">
                                <span>Product Details Page</span>
                            </label>
                            <label class="form-check">
                                <input type="radio" name="action_type" value="external_url" {{ old('action_type', $banner->action_type) == 'external_url' ? 'checked' : '' }} onchange="toggleActionFields()">
                                <span>External URL</span>
                            </label>
                        </div>
                        @error('action_type')<div class="form-error">{{ $message }}</div>@enderror
                        <div style="font-size:0.75rem; color:var(--text-secondary); margin-top:0.375rem;">Visitors can click anywhere on the banner image to be taken to this destination.</div>
                    </div>
                    <div class="form-group" id="productSelect">
                        <label for="action_product_id">Select Product</label>
                        <select id="action_product_id" name="action_product_id" class="form-control">
                            <option value="">Select Product</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" {{ old('action_product_id', $banner->action_product_id) == $product->id ? 'selected' : '' }}>{{ $product->title }}</option>
                            @endforeach
                        </select>
                        @error('action_product_id')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group" id="externalUrlField">
                        <label for="action_url">External URL</label>
                        <input type="url" id="action_url" name="action_url" class="form-control @error('action_url') is-invalid @enderror" value="{{ old('action_url', $banner->action_url) }}" placeholder="https://example.com">
                        @error('action_url')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-section">
                    <h4 class="form-section-title">Options</h4>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="sort_order">Sort Order</label>
                            <input type="number" id="sort_order" name="sort_order" min="0" class="form-control" value="{{ old('sort_order', $banner->sort_order) }}">
                        </div>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $banner->is_active) ? 'checked' : '' }}>
                        <label for="is_active">Active</label>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.banners.index') }}" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Banner</button>
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

    function toggleMediaField() {
        const checked = document.querySelector('input[name="media_type"]:checked');
        const isVideo = checked && checked.value === 'youtube';
        document.getElementById('imageMediaBlock').style.display = isVideo ? 'none' : 'block';
        document.getElementById('videoMediaBlock').style.display = isVideo ? 'block' : 'none';
    }

    function toggleActionFields() {
        const checked = document.querySelector('input[name="action_type"]:checked');
        if (!checked) return;
        const value = checked.value;
        document.getElementById('productSelect').style.display = value === 'product' ? 'block' : 'none';
        document.getElementById('externalUrlField').style.display = value === 'external_url' ? 'block' : 'none';
    }

    document.addEventListener('DOMContentLoaded', function () {
        toggleMediaField();
        toggleActionFields();
    });
</script>
@endpush