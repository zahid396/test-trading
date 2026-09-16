@extends('admin.layouts.app')

@section('title', 'Payment Settings')

@section('content')
    <div class="page-header">
        <h1>Payment Settings</h1>
        <p>Configure your payment methods for checkout.</p>
    </div>

    @php
        $methods = [
            'bkash' => ['label' => 'bKash', 'color' => 'danger'],
            'nagad' => ['label' => 'Nagad', 'color' => 'warning'],
        ];
    @endphp

    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap:1.5rem;">
        @foreach($methods as $key => $method)
            @php
                $setting = $key == 'bkash' ? $bkash : $nagad;
                $isActive = $setting && $setting->is_active;
            @endphp
            <div class="card">
                <div class="card-header">
                    <h3>{{ $method['label'] }} Settings</h3>
                    <label class="toggle">
                        <input type="checkbox" name="{{ $key }}_is_active" value="1"
                               {{ $isActive ? 'checked' : '' }}
                               onchange="togglePaymentActive(this, '{{ $key }}')"
                               data-url="{{ route('admin.payment-settings.toggle', $key) }}">
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                <form method="POST" action="{{ route('admin.payment-settings.update', $key) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="{{ $key }}_number">{{ $method['label'] }} Number</label>
                            <input type="text" id="{{ $key }}_number" name="number" class="form-control" value="{{ old('number', $setting->number ?? '') }}" placeholder="01XXX-XXXXXX" required>
                        </div>
                        <div class="form-group">
                            <label for="{{ $key }}_account_type">Account Type</label>
                            <input type="text" id="{{ $key }}_account_type" name="account_type" class="form-control" value="{{ old('account_type', $setting->account_type ?? '') }}" placeholder="e.g. Personal / Agent / Merchant">
                        </div>
                        <div class="form-group">
                            <label for="{{ $key }}_instructions">Payment Instructions</label>
                            <textarea id="{{ $key }}_instructions" name="instructions" class="form-control" rows="3">{{ old('instructions', $setting->instructions ?? '') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>QR Code Image</label>
                            @if($setting && $setting->qr_image)
                                <div style="margin-bottom:0.75rem;">
                                    <img src="{{ asset('storage/payments/' . $setting->qr_image) }}" style="width:120px; height:120px; object-fit:contain; border:1px solid var(--border); border-radius:var(--radius); background:#fff;">
                                </div>
                            @endif
                            <div class="upload-area" onclick="document.getElementById('{{ $key }}QrInput').click()">
                                <div class="upload-icon">&#128444;</div>
                                <p>Click to upload {{ $method['label'] }} QR code</p>
                                <input type="file" id="{{ $key }}QrInput" name="qr_image" accept="image/*" class="form-control" onchange="previewQr(this, '{{ $key }}')">
                            </div>
                            <div class="upload-preview">
                                <img id="{{ $key }}QrPreview" style="display:none;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Payment Method Logo</label>
                            @if($setting && $setting->logo)
                                <div style="margin-bottom:0.75rem;">
                                    <img src="{{ asset('storage/payments/' . $setting->logo) }}" style="max-height:56px; object-fit:contain; border:1px solid var(--border); border-radius:var(--radius); padding:0.25rem; background:#fff;">
                                </div>
                            @endif
                            <div class="upload-area" onclick="document.getElementById('{{ $key }}LogoInput').click()">
                                <div class="upload-icon">&#128444;</div>
                                <p>Click to upload {{ $method['label'] }} logo</p>
                                <input type="file" id="{{ $key }}LogoInput" name="logo" accept="image/*" class="form-control" onchange="previewLogo(this, '{{ $key }}')">
                            </div>
                            <div class="upload-preview">
                                <img id="{{ $key }}LogoPreview" style="display:none;">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Save {{ $method['label'] }} Settings</button>
                    </div>
                </form>
            </div>
        @endforeach
    </div>
@endsection

@push('scripts')
<script>
    function previewQr(input, key) {
        const preview = document.getElementById(key + 'QrPreview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                preview.style.width = '120px';
                preview.style.height = '120px';
                preview.style.objectFit = 'contain';
                preview.style.border = '1px solid var(--border)';
                preview.style.borderRadius = 'var(--radius)';
                preview.style.background = '#fff';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewLogo(input, key) {
        const preview = document.getElementById(key + 'LogoPreview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                preview.style.maxHeight = '56px';
                preview.style.objectFit = 'contain';
                preview.style.border = '1px solid var(--border)';
                preview.style.borderRadius = 'var(--radius)';
                preview.style.padding = '0.25rem';
                preview.style.background = '#fff';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function togglePaymentActive(el, method) {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const url = el.dataset.url;
        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                el.checked = data.is_active;
            }
        });
    }
</script>
@endpush
