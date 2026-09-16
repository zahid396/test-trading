@extends('layouts.app')

@section('head')
<style>
.error-page { padding: 5rem 0 6rem; text-align: center; }
.error-code { font-size: 6rem; font-weight: 800; color: var(--primary); line-height: 1; margin-bottom: 1rem; }
.error-page h1 { font-size: 1.5rem; font-weight: 700; color: var(--gray-900); margin-bottom: .75rem; }
.error-page p { color: var(--gray-500); max-width: 420px; margin: 0 auto 2rem; line-height: 1.6; }
.error-actions { display: flex; gap: .75rem; justify-content: center; flex-wrap: wrap; }
.error-btn { display: inline-flex; align-items: center; gap: .5rem; padding: .8rem 1.5rem; border-radius: 12px; font-weight: 600; font-size: .92rem; text-decoration: none; transition: var(--transition); }
.error-btn-primary { background: linear-gradient(135deg, #f0b90b, #e2a008); color: #0b0f1c; box-shadow: 0 6px 20px rgba(240,185,11,.25); }
.error-btn-primary:hover { background: linear-gradient(135deg, #eab308, #d4a017); color: #0b0f1c; transform: translateY(-1px); }
.error-btn-secondary { background: var(--gray-100); color: var(--gray-700); }
.error-btn-secondary:hover { background: var(--gray-200); }
@media (max-width: 480px) {
    .error-code { font-size: 4.5rem; }
}
</style>
@endsection

@section('content')
<div class="error-page">
    <div class="container">
        <div class="error-code">404</div>
        <h1>Page Not Found</h1>
        <p>The page you're looking for doesn't exist or may have been moved. Please check the URL or browse our products.</p>
        <div class="error-actions">
            <a href="{{ route('home') }}" class="error-btn error-btn-primary">Back to Home</a>
            <a href="{{ route('products.index') }}" class="error-btn error-btn-secondary">Browse Products</a>
        </div>
    </div>
</div>
@endsection