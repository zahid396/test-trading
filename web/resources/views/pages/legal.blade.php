@extends('layouts.app')
@section('head')
<style>
.legal-page { padding: 3rem 0 4rem; }
.legal-card { max-width: 800px; margin: 0 auto; background: var(--white); border-radius: var(--radius-xl); padding: 3rem 3rem; box-shadow: var(--shadow); border: 1px solid var(--gray-200); }
.legal-card h1 { font-size: 1.75rem; font-weight: 800; color: var(--gray-900); margin-bottom: .75rem; }
.legal-meta { font-size: .82rem; color: var(--gray-400); margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 1px solid var(--gray-200); }
.legal-body { color: var(--gray-600); line-height: 1.9; font-size: .98rem; }
.legal-body h2 { font-size: 1.2rem; font-weight: 700; color: var(--gray-800); margin-top: 2rem; margin-bottom: .75rem; }
.legal-body h3 { font-size: 1.05rem; font-weight: 700; color: var(--gray-700); margin-top: 1.5rem; margin-bottom: .5rem; }
.legal-body p { margin-bottom: 1rem; }
.legal-body ul, .legal-body ol { padding-left: 1.5rem; margin-bottom: 1rem; }
.legal-body li { margin-bottom: .5rem; }
.legal-body strong { color: var(--gray-800); }
.legal-body a { color: var(--primary); text-decoration: underline; }
.legal-body a:hover { color: var(--primary-light); }
.legal-body blockquote { border-left: 3px solid var(--primary); padding: .75rem 1.25rem; margin: 1rem 0; background: var(--primary-50); border-radius: 0 var(--radius) var(--radius) 0; color: var(--gray-700); font-style: italic; }
.legal-nav { display: flex; gap: 1rem; margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--gray-200); flex-wrap: wrap; }
.legal-nav a { font-size: .88rem; font-weight: 500; color: var(--primary); padding: .4rem .85rem; border-radius: var(--radius); transition: var(--transition); }
.legal-nav a:hover { background: var(--primary-50); }

@media (max-width: 768px) {
    .legal-card { padding: 2rem 1.5rem; margin: 0 1rem; }
}
@media (max-width: 480px) {
    .legal-card { padding: 1.5rem 1.25rem; }
}
</style>
@endsection

@section('content')
<div class="legal-page">
    <div class="container">
        <div class="legal-card">
            <h1>{{ $page->title ?? 'Legal Information' }}</h1>
            <div class="legal-meta">Last updated: {{ $page->updated_at ? $page->updated_at->format('d M Y') : date('d M Y') }}</div>
            <div class="legal-body">
                {!! $page->content ?? '<p>Content not available.</p>' !!}
            </div>
            <div class="legal-nav">
                <a href="{{ route('privacy-policy') }}">Privacy Policy</a>
                <a href="{{ route('terms') }}">Terms & Conditions</a>
                <a href="{{ route('refund-policy') }}">Refund Policy</a>
            </div>
        </div>
    </div>
</div>
@endsection
