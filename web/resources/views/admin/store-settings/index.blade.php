@extends('admin.layouts.app')

@section('title', 'Store Settings')

@section('content')
    <div class="page-header">
        <h1>Store Settings</h1>
        <p>Configure your store's general information.</p>
    </div>

    <form method="POST" action="{{ route('admin.store-settings.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-body">
                <div class="form-section">
                    <h4 class="form-section-title">Store Identity</h4>
                    <div class="form-group">
                        <label for="store_name">Store Name <span class="required">*</span></label>
                        <input type="text" id="store_name" name="store_name" class="form-control @error('store_name') is-invalid @enderror" value="{{ old('store_name', $settings['store_name']) }}" required>
                        @error('store_name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Store Logo</label>
                            @if($settings['store_logo'])
                                <div style="margin-bottom:0.75rem;">
                                    <img src="{{ asset('storage/store/' . $settings['store_logo']) }}" style="max-height:80px; border:1px solid var(--border); border-radius:var(--radius);">
                                </div>
                            @endif
                            <div class="upload-area" onclick="document.getElementById('logoInput').click()">
                                <div class="upload-icon">&#128444;</div>
                                <p>Click to upload logo</p>
                                <input type="file" id="logoInput" name="store_logo" accept="image/*" class="form-control" onchange="previewLogo(this)">
                            </div>
                            <div class="upload-preview">
                                <img id="logoPreview" style="display:none;">
                            </div>
                            @error('store_logo')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label>Favicon</label>
                            @if($settings['store_favicon'])
                                <div style="margin-bottom:0.75rem;">
                                    <img src="{{ asset('storage/store/' . $settings['store_favicon']) }}" style="width:48px; height:48px; border:1px solid var(--border); border-radius:var(--radius);">
                                </div>
                            @endif
                            <div class="upload-area" onclick="document.getElementById('faviconInput').click()">
                                <div class="upload-icon">&#128444;</div>
                                <p>Click to upload favicon</p>
                                <input type="file" id="faviconInput" name="store_favicon" accept="image/*" class="form-control" onchange="previewFavicon(this)">
                            </div>
                            <div class="upload-preview">
                                <img id="faviconPreview" style="display:none;">
                            </div>
                            @error('store_favicon')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h4 class="form-section-title">About</h4>
                    <div class="form-group">
                        <label for="about_text">About Text</label>
                        <textarea id="about_text" name="about_text" class="form-control @error('about_text') is-invalid @enderror" rows="5">{{ old('about_text', $settings['about_text']) }}</textarea>
                        @error('about_text')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-section">
                    <h4 class="form-section-title">Homepage Hero (Trading Courses &amp; Careers)</h4>
                    <p style="margin-bottom:1rem; font-size:0.8rem; color:var(--text-secondary);">Leave any field empty to keep its default value. Uploading an image replaces the animated market chart card on the homepage.</p>
                    <div class="form-group">
                        <label for="hero_badge">Hero Badge Text</label>
                        <input type="text" id="hero_badge" name="hero_badge" class="form-control @error('hero_badge') is-invalid @enderror" value="{{ old('hero_badge', $settings['hero_badge']) }}" placeholder="Trading Courses &amp; Careers">
                        @error('hero_badge')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="hero_title">Headline Title</label>
                            <input type="text" id="hero_title" name="hero_title" class="form-control @error('hero_title') is-invalid @enderror" value="{{ old('hero_title', $settings['hero_title']) }}" placeholder="Master the Markets. Trade with">
                            @error('hero_title')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="hero_title_highlight">Headline Highlight (gold text)</label>
                            <input type="text" id="hero_title_highlight" name="hero_title_highlight" class="form-control @error('hero_title_highlight') is-invalid @enderror" value="{{ old('hero_title_highlight', $settings['hero_title_highlight']) }}" placeholder="Confidence.">
                            @error('hero_title_highlight')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="hero_subtitle">Hero Subtitle</label>
                        <textarea id="hero_subtitle" name="hero_subtitle" class="form-control @error('hero_subtitle') is-invalid @enderror" rows="3" placeholder="Premium trading courses, expert mentorship and job placement support — built for traders who want real, lasting results.">{{ old('hero_subtitle', $settings['hero_subtitle']) }}</textarea>
                        @error('hero_subtitle')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="hero_cta1_text">Button 1 Text</label>
                            <input type="text" id="hero_cta1_text" name="hero_cta1_text" class="form-control @error('hero_cta1_text') is-invalid @enderror" value="{{ old('hero_cta1_text', $settings['hero_cta1_text']) }}" placeholder="Explore Courses">
                            @error('hero_cta1_text')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="hero_cta1_url">Button 1 URL</label>
                            <input type="url" id="hero_cta1_url" name="hero_cta1_url" class="form-control @error('hero_cta1_url') is-invalid @enderror" value="{{ old('hero_cta1_url', $settings['hero_cta1_url']) }}" placeholder="/products or https://...">
                            @error('hero_cta1_url')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="hero_cta2_text">Button 2 Text</label>
                            <input type="text" id="hero_cta2_text" name="hero_cta2_text" class="form-control @error('hero_cta2_text') is-invalid @enderror" value="{{ old('hero_cta2_text', $settings['hero_cta2_text']) }}" placeholder="Track Your Order">
                            @error('hero_cta2_text')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="hero_cta2_url">Button 2 URL</label>
                            <input type="url" id="hero_cta2_url" name="hero_cta2_url" class="form-control @error('hero_cta2_url') is-invalid @enderror" value="{{ old('hero_cta2_url', $settings['hero_cta2_url']) }}" placeholder="/order/track or https://...">
                            @error('hero_cta2_url')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Hero Stats <span style="font-weight:400; font-size:0.75rem; color:var(--text-secondary);">(value + label)</span></label>
                        <div class="form-row">
                            <div class="form-group">
                                <input type="text" name="hero_stat1_value" class="form-control @error('hero_stat1_value') is-invalid @enderror" value="{{ old('hero_stat1_value', $settings['hero_stat1_value']) }}" placeholder="500+">
                                @error('hero_stat1_value')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group">
                                <input type="text" name="hero_stat1_label" class="form-control @error('hero_stat1_label') is-invalid @enderror" value="{{ old('hero_stat1_label', $settings['hero_stat1_label']) }}" placeholder="Students Trained">
                                @error('hero_stat1_label')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <input type="text" name="hero_stat2_value" class="form-control @error('hero_stat2_value') is-invalid @enderror" value="{{ old('hero_stat2_value', $settings['hero_stat2_value']) }}" placeholder="30+">
                                @error('hero_stat2_value')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group">
                                <input type="text" name="hero_stat2_label" class="form-control @error('hero_stat2_label') is-invalid @enderror" value="{{ old('hero_stat2_label', $settings['hero_stat2_label']) }}" placeholder="Course Modules">
                                @error('hero_stat2_label')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <input type="text" name="hero_stat3_value" class="form-control @error('hero_stat3_value') is-invalid @enderror" value="{{ old('hero_stat3_value', $settings['hero_stat3_value']) }}" placeholder="Job &amp;">
                                @error('hero_stat3_value')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group">
                                <input type="text" name="hero_stat3_label" class="form-control @error('hero_stat3_label') is-invalid @enderror" value="{{ old('hero_stat3_label', $settings['hero_stat3_label']) }}" placeholder="Placement Support">
                                @error('hero_stat3_label')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label>Hero Card Image <span style="font-weight:400; font-size:0.75rem; color:var(--text-secondary);">(optional — replaces the animated chart card)</span></label>
                        @if($settings['hero_image'])
                            <div style="margin-bottom:0.75rem;">
                                <img src="{{ asset('storage/store/' . $settings['hero_image']) }}" style="max-width:300px; border:1px solid var(--border); border-radius:var(--radius);">
                            </div>
                        @endif
                        <div class="upload-area" onclick="document.getElementById('heroImageInput').click()">
                            <div class="upload-icon">&#128444;</div>
                            <p>Click to upload hero card image</p>
                            <input type="file" id="heroImageInput" name="hero_image" accept="image/*" class="form-control" onchange="previewHeroImage(this)">
                        </div>
                        <div class="upload-preview">
                            <img id="heroImagePreview" style="display:none;">
                        </div>
                        @error('hero_image')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-section">
                    <h4 class="form-section-title">Contact Information</h4>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="contact_email">Contact Email</label>
                            <input type="email" id="contact_email" name="contact_email" class="form-control @error('contact_email') is-invalid @enderror" value="{{ old('contact_email', $settings['contact_email']) }}" placeholder="support@example.com">
                            @error('contact_email')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="contact_phone">Contact Phone</label>
                            <input type="text" id="contact_phone" name="contact_phone" class="form-control @error('contact_phone') is-invalid @enderror" value="{{ old('contact_phone', $settings['contact_phone']) }}" placeholder="+880 1XXX-XXXXXX">
                            @error('contact_phone')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h4 class="form-section-title">Footer Text</h4>
                    <div class="form-group">
                        <label for="copyright_text">Copyright Text</label>
                        <input type="text" id="copyright_text" name="copyright_text" class="form-control @error('copyright_text') is-invalid @enderror" value="{{ old('copyright_text', $settings['copyright_text']) }}" placeholder="&copy; {{ date('Y') }} Your Store. All rights reserved.">
                        @error('copyright_text')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label for="footer_text">Footer Text</label>
                        <textarea id="footer_text" name="footer_text" class="form-control @error('footer_text') is-invalid @enderror" rows="3">{{ old('footer_text', $settings['footer_text']) }}</textarea>
                        @error('footer_text')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Save Settings</button>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
<script>
    function previewLogo(input) {
        const preview = document.getElementById('logoPreview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                preview.style.maxHeight = '80px';
                preview.style.border = '1px solid var(--border)';
                preview.style.borderRadius = 'var(--radius)';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewFavicon(input) {
        const preview = document.getElementById('faviconPreview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                preview.style.width = '48px';
                preview.style.height = '48px';
                preview.style.border = '1px solid var(--border)';
                preview.style.borderRadius = 'var(--radius)';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewHeroImage(input) {
        const preview = document.getElementById('heroImagePreview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                preview.style.maxWidth = '300px';
                preview.style.border = '1px solid var(--border)';
                preview.style.borderRadius = 'var(--radius)';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
