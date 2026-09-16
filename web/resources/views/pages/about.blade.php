@extends('layouts.app')
@section('head')
<style>
.about-page { padding: 3rem 0 4rem; }
.about-hero { text-align: center; margin-bottom: 3rem; }
.about-hero h1 { font-size: 2rem; font-weight: 800; color: var(--gray-900); margin-bottom: .5rem; }
.about-hero .accent-line { width: 48px; height: 3px; background: linear-gradient(135deg, #f0b90b, #e2a008); border-radius: 4px; margin: .75rem auto 1.25rem; box-shadow: 0 0 12px rgba(240,185,11,.5); }
.about-hero p { color: var(--gray-500); font-size: 1.05rem; max-width: 520px; margin: 0 auto; line-height: 1.7; }
.about-card { max-width: 800px; margin: 0 auto; background: var(--white); border-radius: var(--radius-xl); padding: 3rem; box-shadow: var(--shadow); border: 1px solid var(--gray-200); }
.about-card h2 { font-size: 1.35rem; font-weight: 800; color: var(--gray-900); margin-bottom: .75rem; }
.about-card .about-text { color: var(--gray-600); line-height: 1.9; font-size: 1rem; margin-bottom: 2rem; }
.about-features { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.25rem; margin-bottom: 2.5rem; }
.about-feature { text-align: center; padding: 1.5rem; background: var(--gray-50); border-radius: var(--radius-lg); border: 1px solid var(--gray-100); transition: var(--transition); }
.about-feature:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
.about-feature-icon { width: 48px; height: 48px; border-radius: 50%; background: var(--primary-100); color: var(--primary); display: flex; align-items: center; justify-content: center; margin: 0 auto .75rem; font-size: 1.2rem; }
.about-feature h3 { font-size: .95rem; font-weight: 700; color: var(--gray-800); margin-bottom: .3rem; }
.about-feature p { font-size: .82rem; color: var(--gray-500); line-height: 1.5; }
.contact-section { background: var(--gray-50); border-radius: var(--radius-lg); padding: 2rem; text-align: center; border: 1px solid var(--gray-100); }
.contact-section h2 { font-size: 1.15rem; font-weight: 700; color: var(--gray-900); margin-bottom: 1rem; }
.contact-cards { display: flex; justify-content: center; gap: 1.5rem; flex-wrap: wrap; }
.contact-card { display: flex; align-items: center; gap: .75rem; padding: 1rem 1.5rem; background: var(--white); border-radius: var(--radius); border: 1px solid var(--gray-200); transition: var(--transition); }
.contact-card:hover { border-color: var(--primary); transform: translateY(-2px); box-shadow: var(--shadow-md); }
.contact-card .c-icon { width: 40px; height: 40px; border-radius: 50%; background: var(--primary-100); color: var(--primary); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.contact-card .c-info { text-align: left; }
.contact-card .c-label { font-size: .75rem; color: var(--gray-400); text-transform: uppercase; letter-spacing: .5px; font-weight: 600; }
.contact-card .c-value { font-weight: 600; color: var(--gray-800); font-size: .9rem; }

@media (max-width: 768px) {
    .about-card { padding: 2rem 1.5rem; margin: 0 1rem; }
    .about-hero h1 { font-size: 1.5rem; }
    .contact-cards { flex-direction: column; align-items: center; }
}
</style>
@endsection

@section('content')
<div class="about-page">
    <div class="container">
        <div class="about-hero">
            <h1>About {{ $storeSettings['store_name'] ?? 'Us' }}</h1>
            <div class="accent-line"></div>
            @if(!empty($storeSettings['about_text']))
                <p>{{ Str::limit($storeSettings['about_text'], 200) }}</p>
            @else
                <p>We deliver premium digital products with excellent customer support.</p>
            @endif
        </div>

        <div class="about-card">
            @if(!empty($storeSettings['about_text']))
            <h2>Our Story</h2>
            <div class="about-text">
                {!! nl2br(e($storeSettings['about_text'])) !!}
            </div>
            @endif

            <div class="about-features">
                <div class="about-feature">
                    <div class="about-feature-icon">
                        <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                    </div>
                    <h3>Instant Delivery</h3>
                    <p>Receive your digital products instantly after payment verification</p>
                </div>
                <div class="about-feature">
                    <div class="about-feature-icon">
                        <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </div>
                    <h3>Secure Payments</h3>
                    <p>100% secure transactions via bKash and Nagad</p>
                </div>
                <div class="about-feature">
                    <div class="about-feature-icon">
                        <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    </div>
                    <h3>24/7 Support</h3>
                    <p>Reach us anytime through our multiple support channels</p>
                </div>
            </div>

            <div class="contact-section">
                <h2>Get In Touch</h2>
                <div class="contact-cards">
                    @if(!empty($storeSettings['contact_email']))
                    <a href="mailto:{{ $storeSettings['contact_email'] }}" class="contact-card">
                        <div class="c-icon">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M2 4l7 5 7-5M2 4h14v10H2z"/></svg>
                        </div>
                        <div class="c-info">
                            <div class="c-label">Email</div>
                            <div class="c-value">{{ $storeSettings['contact_email'] }}</div>
                        </div>
                    </a>
                    @endif
                    @if(!empty($storeSettings['contact_phone']))
                    <a href="tel:{{ $storeSettings['contact_phone'] }}" class="contact-card">
                        <div class="c-icon">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M17 13.5V16a1 1 0 0 1-1.1 1A15.9 15.9 0 0 1 2.5 3.1 1 1 0 0 1 3.5 2H6a1 1 0 0 1 1 .8c.1.5.4 1.1.7 1.6a1 1 0 0 1-.2 1.1L5.8 7.3a12 12 0 0 0 4.9 4.9l1.8-1.7a1 1 0 0 1 1.1-.2c.5.3 1.1.5 1.6.7A1 1 0 0 1 17 13.5z"/></svg>
                        </div>
                        <div class="c-info">
                            <div class="c-label">Phone</div>
                            <div class="c-value">{{ $storeSettings['contact_phone'] }}</div>
                        </div>
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
