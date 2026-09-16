<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $storeSettings['store_name'] ?? config('app.name') }}</title>
    @if(!empty($storeSettings['store_favicon']))
    <link rel="icon" type="image/png" href="{{ asset('storage/store/' . $storeSettings['store_favicon']) }}">
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --primary: #f0b90b; --primary-dark: #d4a017; --primary-light: #fcd34d;
            --primary-50: rgba(240,185,11,.12); --primary-100: rgba(240,185,11,.2);
            --accent: #16c784; --accent-dark: #118a5c;
            --up: #16c784; --down: #f6465d; --amber: #f59e0b;
            --gray-50: #0a0e1a; --gray-100: #10182a; --gray-200: #1f2a44; --gray-300: #2c3a5e;
            --gray-400: #5b6b8c; --gray-500: #8393b3; --gray-600: #a8b6d0; --gray-700: #c3cde4;
            --gray-800: #dde5f2; --gray-900: #f4f7fc;
            --white: #131a2e;
            --shadow-sm: 0 1px 2px rgba(0,0,0,.25);
            --shadow: 0 1px 3px rgba(0,0,0,.4), 0 1px 2px rgba(0,0,0,.35);
            --shadow-md: 0 4px 6px rgba(0,0,0,.45), 0 2px 4px rgba(0,0,0,.4);
            --shadow-lg: 0 10px 15px rgba(0,0,0,.5), 0 4px 6px rgba(0,0,0,.45);
            --shadow-xl: 0 20px 25px rgba(0,0,0,.55), 0 10px 10px rgba(0,0,0,.45);
            --radius: .75rem; --radius-lg: 1rem; --radius-xl: 1.25rem;
            --transition: all .2s ease;
            --font: 'Manrope', 'Segoe UI', system-ui, -apple-system, sans-serif;
            --font-display: 'Sora', 'Manrope', 'Segoe UI', sans-serif;
        }
        html { scroll-behavior: smooth; font-family: var(--font); color: var(--gray-800); background: var(--gray-50); color-scheme: dark; }
        body { line-height: 1.6; min-height: 100vh; display: flex; flex-direction: column; background: var(--gray-50); }
        img { max-width: 100%; display: block; }
        a { color: var(--primary); text-decoration: none; transition: var(--transition); }
        a:hover { color: var(--primary-light); }
        .container { width: 100%; max-width: 1200px; margin: 0 auto; padding: 0 1rem; }
        h1, h2, h3, h4, h5, h6 { font-family: var(--font-display); letter-spacing: -.01em; }
        ::-webkit-scrollbar { width: 10px; height: 10px; }
        ::-webkit-scrollbar-track { background: #0d1322; }
        ::-webkit-scrollbar-thumb { background: #2a3752; border-radius: 6px; }
        ::-webkit-scrollbar-thumb:hover { background: #3a4a6e; }

        /* ── Navbar ── */
        .navbar { position: sticky; top: 0; z-index: 100; background: rgba(10,14,26,.85); backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px); border-bottom: 1px solid var(--gray-200); box-shadow: 0 1px 0 rgba(240,185,11,.05); }
        .navbar .container { display: flex; align-items: center; justify-content: space-between; height: 64px; }
        .nav-brand { display: flex; align-items: center; gap: .6rem; font-weight: 700; font-size: 1.2rem; color: var(--gray-900); font-family: var(--font-display); }
        .nav-brand img { height: 36px; width: auto; border-radius: .5rem; object-fit: contain; }
        .nav-links { display: flex; align-items: center; gap: .25rem; }
        .nav-links a { padding: .5rem 1rem; border-radius: var(--radius); font-weight: 500; font-size: .935rem; color: var(--gray-600); transition: var(--transition); }
        .nav-links a:hover, .nav-links a.active { color: var(--primary); background: var(--primary-50); }
        .hamburger { display: none; flex-direction: column; gap: 5px; background: none; border: none; cursor: pointer; padding: .5rem; }
        .hamburger span { display: block; width: 22px; height: 2px; background: var(--gray-700); border-radius: 2px; transition: var(--transition); }
        .mobile-menu { display: none; position: fixed; top: 64px; left: 0; right: 0; background: var(--white); border-bottom: 1px solid var(--gray-200); box-shadow: var(--shadow-lg); z-index: 99; padding: 1rem; }
        .mobile-menu.open { display: block; animation: slideDown .2s ease; }
        .mobile-menu a { display: block; padding: .75rem 1rem; border-radius: var(--radius); font-weight: 500; color: var(--gray-600); }
        .mobile-menu a:hover, .mobile-menu a.active { color: var(--primary); background: var(--primary-50); }
        @keyframes slideDown { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }

        /* ── Main ── */
        main { flex: 1; }

        /* ── Footer ── */
        .footer { background: #070b15; color: #a8b6d0; padding: 3rem 0 1.5rem; margin-top: auto; border-top: 1px solid #16203a; }
        .footer-grid { display: grid; grid-template-columns: 1.5fr 1fr 1fr 1fr; gap: 2rem; }
        .footer-brand { display: flex; align-items: center; gap: .5rem; font-weight: 700; font-size: 1.15rem; color: #f4f7fc; margin-bottom: .75rem; font-family: var(--font-display); }
        .footer-brand img { height: 32px; border-radius: .5rem; object-fit: contain; }
        .footer-about { font-size: .9rem; line-height: 1.7; color: #8393b3; max-width: 320px; }
        .footer h4 { color: #f4f7fc; font-size: .95rem; font-weight: 600; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: .5px; }
        .footer ul { list-style: none; }
        .footer ul li { margin-bottom: .5rem; }
        .footer ul li a { color: #8393b3; font-size: .9rem; transition: var(--transition); }
        .footer ul li a:hover { color: var(--primary-light); padding-left: 4px; }
        .footer-social { display: flex; gap: .75rem; margin-top: 1.25rem; }
        .footer-social a { display: flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 50%; background: #1a2440; color: #8393b3; font-size: .85rem; transition: var(--transition); }
        .footer-social a:hover { background: var(--primary); color: #0b0f1c; transform: translateY(-2px); }
        .footer-bottom { border-top: 1px solid #1a2440; margin-top: 2rem; padding-top: 1.5rem; text-align: center; font-size: .85rem; color: #5b6b8c; }
        .footer-legal { display: flex; justify-content: center; gap: 1.5rem; margin-top: .75rem; }
        .footer-legal a { color: #5b6b8c; font-size: .82rem; }
        .footer-legal a:hover { color: var(--primary-light); }

        /* ── Support Button & Modal ── */
        .support-btn { position: fixed; bottom: 1.5rem; right: 1.5rem; z-index: 200; width: 56px; height: 56px; border-radius: 50%; background: linear-gradient(135deg, #f0b90b, #e2a008); color: #0b0f1c; border: none; cursor: pointer; box-shadow: var(--shadow-xl), 0 0 0 0 rgba(240,185,11,.45); display: flex; align-items: center; justify-content: center; font-size: 1.4rem; transition: var(--transition); animation: pulse-ring 2s infinite; }
        .support-btn:hover { transform: scale(1.08); box-shadow: var(--shadow-xl); }
        @keyframes pulse-ring { 0% { box-shadow: var(--shadow-xl), 0 0 0 0 rgba(240,185,11,.45); } 70% { box-shadow: var(--shadow-xl), 0 0 0 14px rgba(240,185,11,0); } 100% { box-shadow: var(--shadow-xl), 0 0 0 0 rgba(240,185,11,0); } }
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.45); z-index: 300; align-items: center; justify-content: center; backdrop-filter: blur(4px); }
        .modal-overlay.open { display: flex; animation: fadeIn .2s ease; }
        .modal-box { background: var(--white); border-radius: var(--radius-xl); padding: 2rem; max-width: 420px; width: 90%; box-shadow: var(--shadow-xl); animation: modalUp .25s ease; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes modalUp { from { opacity: 0; transform: translateY(20px) scale(.96); } to { opacity: 1; transform: translateY(0) scale(1); } }
        .modal-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; }
        .modal-header h3 { font-size: 1.15rem; font-weight: 700; color: var(--gray-900); }
        .modal-close { background: none; border: none; font-size: 1.4rem; color: var(--gray-400); cursor: pointer; padding: .25rem; line-height: 1; transition: var(--transition); }
        .modal-close:hover { color: var(--gray-700); }
        .support-channels { display: flex; flex-direction: column; gap: .75rem; }
        .support-channel { display: flex; align-items: center; gap: .75rem; padding: 1rem; border-radius: var(--radius); border: 1px solid var(--gray-200); transition: var(--transition); text-decoration: none; color: var(--gray-700); }
        .support-channel:hover { border-color: var(--primary); background: var(--primary-50); color: var(--primary); transform: translateX(4px); }
        .support-channel .ch-icon { width: 42px; height: 42px; border-radius: .5rem; background: var(--primary-100); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0; }
        .support-channel .ch-info { font-weight: 600; font-size: .95rem; }
        .support-channel .ch-sub { font-size: .8rem; color: var(--gray-400); font-weight: 400; }

        /* ── Market Ticker ── */
        .ticker { background: #070b15; border-top: 1px solid var(--gray-200); border-bottom: 1px solid var(--gray-200); overflow: hidden; white-space: nowrap; position: relative; }
        .ticker-track { display: inline-flex; align-items: center; gap: 3rem; padding: .7rem 0; animation: ticker-scroll 32s linear infinite; will-change: transform; }
        .ticker:hover .ticker-track { animation-play-state: paused; }
        .ticker-item { display: inline-flex; align-items: center; gap: .55rem; font-family: var(--font-display); font-size: .82rem; font-weight: 600; color: var(--gray-500); letter-spacing: .02em; }
        .ticker-item b { color: var(--gray-800); font-weight: 700; }
        .ticker-item .val { color: var(--gray-600); }
        .ticker-item .up { color: var(--up); font-weight: 700; }
        .ticker-item .down { color: var(--down); font-weight: 700; }
        @keyframes ticker-scroll { from { transform: translateX(0); } to { transform: translateX(-50%); } }

        /* ── Job Cards & Jobs Pages ── */
        .jobs-header { padding: 3rem 0 1.5rem; text-align: center; }
        .jobs-header h1 { font-size: 1.9rem; font-weight: 800; color: var(--gray-900); }
        .jobs-header p { color: var(--gray-500); margin-top: .5rem; font-size: 1rem; }
        .jobs-toolbar { display: flex; flex-wrap: wrap; gap: .75rem; align-items: center; justify-content: center; margin-bottom: 2rem; }
        .jobs-toolbar .jt-input { background: var(--gray-100); border: 1px solid var(--gray-200); border-radius: 50px; padding: .6rem 1.25rem; font-size: .9rem; color: var(--gray-800); min-width: 220px; }
        .jobs-toolbar .jt-input:focus { outline: none; border-color: var(--primary); }
        .jobs-toolbar select.jt-input { min-width: 160px; }
        .jobs-toolbar .jt-btn { border-radius: 50px; padding: .6rem 1.4rem; border: none; background: linear-gradient(135deg, #f0b90b, #e2a008); color: #0b0f1c; font-weight: 700; cursor: pointer; font-size: .9rem; }
        .jcard-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 1.25rem; }
        .jcard { background: var(--white); border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow); cursor: pointer; border: 1px solid var(--gray-200); transition: var(--transition); display: flex; flex-direction: column; }
        .jcard:hover { transform: translateY(-4px); box-shadow: var(--shadow-lg); border-color: var(--primary-100); }
        .jcard-img { position: relative; aspect-ratio: 16/9; overflow: hidden; background: var(--gray-100); }
        .jcard-img img { width: 100%; height: 100%; object-fit: cover; }
        .jcard-placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: var(--gray-100); }
        .jcard-badge { position: absolute; top: .75rem; left: .75rem; background: var(--primary); color: #0b0f1c; font-size: .72rem; font-weight: 700; padding: .25rem .65rem; border-radius: 50px; }
        .jcard-closed { position: absolute; top: .75rem; right: .75rem; background: rgba(0,0,0,.6); color: #dde5f2; font-size: .72rem; font-weight: 600; padding: .25rem .65rem; border-radius: 50px; }
        .jcard-body { padding: 1.1rem 1.15rem 1.3rem; display: flex; flex-direction: column; flex: 1; }
        .jcard-title { font-size: 1.02rem; font-weight: 700; color: var(--gray-900); margin-bottom: .2rem; line-height: 1.35; }
        .jcard-company { font-size: .82rem; color: var(--primary); font-weight: 600; margin-bottom: .4rem; }
        .jcard-subtitle { font-size: .82rem; color: var(--gray-500); margin-bottom: .7rem; line-height: 1.5; }
        .jcard-meta { display: flex; flex-wrap: wrap; gap: .5rem 1rem; font-size: .8rem; color: var(--gray-600); margin-bottom: .7rem; }
        .jcard-deadline { font-size: .78rem; color: var(--gray-400); margin-bottom: .9rem; }
        .jcard-deadline strong { color: var(--gray-600); }
        .jcard-btn { margin-top: auto; width: 100%; padding: .6rem; background: var(--primary-50); color: var(--primary); font-weight: 600; font-size: .88rem; border: 1px solid rgba(240,185,11,.35); border-radius: var(--radius); cursor: pointer; transition: var(--transition); }
        .jcard-btn:hover { background: linear-gradient(135deg, #f0b90b, #e2a008); color: #0b0f1c; border-color: transparent; }
        .job-detail-card { background: var(--white); border: 1px solid var(--gray-200); border-radius: var(--radius-xl); overflow: hidden; box-shadow: var(--shadow-md); }
        .job-detail-hero { padding: 2.25rem 2rem; display: flex; align-items: flex-start; gap: 1.5rem; border-bottom: 1px solid var(--gray-200); flex-wrap: wrap; }
        .job-detail-logo { width: 64px; height: 64px; border-radius: var(--radius-lg); overflow: hidden; background: var(--gray-100); flex-shrink: 0; display: flex; align-items: center; justify-content: center; border: 1px solid var(--gray-200); }
        .job-detail-logo img { width: 100%; height: 100%; object-fit: cover; }
        .job-detail-title { font-size: 1.5rem; font-weight: 800; color: var(--gray-900); margin-bottom: .35rem; }
        .job-detail-company { font-size: 1rem; color: var(--primary); font-weight: 600; margin-bottom: .5rem; }
        .job-detail-chip { display: inline-flex; align-items: center; gap: .35rem; font-size: .8rem; color: var(--gray-600); background: var(--gray-100); padding: .35rem .8rem; border-radius: 50px; margin-right: .5rem; }
        .job-detail-chip .dot { width: 6px; height: 6px; border-radius: 50%; background: var(--accent); }
        .job-detail-deadline { margin-left: auto; text-align: right; font-size: .82rem; color: var(--gray-500); }
        .job-detail-deadline b { display: block; font-size: .9rem; color: var(--gray-800); }
        .job-detail-body { padding: 2rem; }
        .job-detail-body h3 { font-size: 1.15rem; font-weight: 700; color: var(--gray-900); margin-bottom: 1rem; }
        .job-detail-body p { color: var(--gray-600); line-height: 1.85; font-size: .95rem; margin-bottom: 1rem; white-space: pre-line; }
        .job-detail-body ul { list-style: none; margin-bottom: 1.5rem; }
        .job-detail-body ul li { padding: .35rem 0; font-size: .92rem; color: var(--gray-600); display: flex; align-items: flex-start; gap: .6rem; }
        .job-detail-body ul li::before { content: '✓'; color: var(--accent); font-weight: 700; flex-shrink: 0; }
        .apply-box { background: var(--white); border: 1px solid var(--gray-200); border-radius: var(--radius-xl); padding: 2rem; margin-top: 2rem; box-shadow: var(--shadow-md); }
        .apply-box h3 { font-size: 1.25rem; font-weight: 800; color: var(--gray-900); margin-bottom: .5rem; }
        .apply-box .apply-hint { color: var(--gray-500); font-size: .88rem; margin-bottom: 1.5rem; }
        .apply-field { margin-bottom: 1.1rem; }
        .apply-field label { display: block; font-size: .85rem; font-weight: 600; color: var(--gray-700); margin-bottom: .4rem; }
        .apply-field input, .apply-field textarea { width: 100%; background: var(--gray-100); border: 1px solid var(--gray-200); border-radius: var(--radius); padding: .7rem .9rem; font-size: .9rem; color: var(--gray-800); font-family: inherit; transition: var(--transition); }
        .apply-field input:focus, .apply-field textarea:focus { outline: none; border-color: var(--primary); background: var(--gray-50); }
        .apply-field.is-invalid input, .apply-field.is-invalid textarea { border-color: var(--down); }
        .apply-error { font-size: .75rem; color: var(--down); margin-top: .3rem; }
        .apply-submit { width: 100%; padding: .85rem; border: none; border-radius: var(--radius); background: linear-gradient(135deg, #f0b90b, #e2a008); color: #0b0f1c; font-family: var(--font-display); font-weight: 700; font-size: 1rem; cursor: pointer; transition: var(--transition); }
        .apply-submit:hover { opacity: .92; transform: translateY(-1px); }
        .job-closed-note { padding: 1rem 1.25rem; border-radius: var(--radius); background: var(--primary-50); border: 1px solid rgba(240,185,11,.35); color: var(--gray-700); font-size: .9rem; margin-top: 1.5rem; }
        .pagination { display: flex; justify-content: center; gap: .35rem; margin-top: 2.5rem; padding: 0; }
        .pagination a, .pagination span { display: inline-flex; align-items: center; justify-content: center; min-width: 40px; height: 40px; padding: 0 .75rem; border-radius: var(--radius); font-weight: 500; font-size: .9rem; transition: var(--transition); }
        .pagination a { background: var(--white); color: var(--gray-600); border: 1px solid var(--gray-200); }
        .pagination a:hover { border-color: var(--primary); color: var(--primary); background: var(--primary-50); }
        .pagination .current { background: linear-gradient(135deg, #f0b90b, #e2a008); color: #0b0f1c; border: 1px solid #f0b90b; font-weight: 700; }
        .pagination .disabled { color: var(--gray-300); pointer-events: none; }
        .empty-state { text-align: center; padding: 4rem 1rem; }
        .empty-state svg { margin: 0 auto 1rem; opacity: .4; }
        .empty-state h3 { color: var(--gray-700); font-size: 1.15rem; margin-bottom: .5rem; }
        .empty-state p { color: var(--gray-400); }
        @media (max-width: 768px) {
            .job-detail-hero { flex-direction: column; align-items: flex-start; }
            .job-detail-deadline { margin-left: 0; text-align: left; margin-top: .5rem; }
            .jobs-header { padding-top: 2.25rem; }
            .jobs-toolbar .jt-input { width: 100%; }
        }

        /* ── Utility / Responsive ── */
        @media (max-width: 768px) {
            .nav-links { display: none; }
            .hamburger { display: flex; }
            .footer-grid { grid-template-columns: 1fr 1fr; }
            .footer-about { max-width: 100%; }
        }
        @media (max-width: 480px) {
            .footer-grid { grid-template-columns: 1fr; gap: 1.5rem; }
        }
    </style>
    @yield('head')
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="{{ route('home') }}" class="nav-brand">
                @if(!empty($storeSettings['store_logo']))
                    <img src="{{ asset('storage/store/' . $storeSettings['store_logo']) }}" alt="{{ $storeSettings['store_name'] }}">
                @endif
                {{ $storeSettings['store_name'] ?? config('app.name') }}
            </a>
            <div class="nav-links">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
<a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'active' : '' }}">Products</a>
                <a href="{{ route('jobs.index') }}" class="{{ request()->routeIs('jobs.*') ? 'active' : '' }}">Jobs</a>
                <a href="{{ route('home') }}#reviews" class="{{ request()->query('scroll') === 'reviews' ? 'active' : '' }}">Reviews</a>
                <a href="{{ route('home') }}#about" class="{{ request()->query('scroll') === 'about' ? 'active' : '' }}">About</a>
            </div>
            <button class="hamburger" id="hamburgerBtn" aria-label="Menu">
                <span></span><span></span><span></span>
            </button>
        </div>
        <div class="mobile-menu" id="mobileMenu">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
            <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'active' : '' }}">Products</a>
            <a href="{{ route('jobs.index') }}" class="{{ request()->routeIs('jobs.*') ? 'active' : '' }}">Jobs</a>
            <a href="{{ route('home') }}#reviews">Reviews</a>
            <a href="{{ route('home') }}#about">About</a>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div>
                    <div class="footer-brand">
                        @if(!empty($storeSettings['store_logo']))
                            <img src="{{ asset('storage/store/' . $storeSettings['store_logo']) }}" alt="{{ $storeSettings['store_name'] }}">
                        @endif
                        {{ $storeSettings['store_name'] ?? '' }}
                    </div>
                    @if(!empty($storeSettings['about_text']))
                        <p class="footer-about">{{ Str::limit($storeSettings['about_text'], 180) }}</p>
                    @endif
                    @if($socialLinks && $socialLinks->count())
                        <div class="footer-social">
                            @foreach($socialLinks as $link)
                                @if($link->is_active)
                                    <a href="{{ $link->url }}" target="_blank" rel="noopener" title="{{ $link->label }}">@include('partials.platform-icon', ['platform' => $link->platform, 'size' => 18])</a>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
                <div>
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('products.index') }}">All Products</a></li>
                        <li><a href="{{ route('jobs.index') }}">Job Circulars</a></li>
                        <li><a href="{{ route('home') }}#reviews">Reviews</a></li>
                        <li><a href="{{ route('home') }}#about">About Us</a></li>
                    </ul>
                </div>
                <div>
                    <h4>Support</h4>
                    <ul>
                        <li><a href="{{ route('order.track') }}">Track Order</a></li>
                        @if(!empty($storeSettings['contact_email']))
                            <li><a href="mailto:{{ $storeSettings['contact_email'] }}">Email Us</a></li>
                        @endif
                        @if(!empty($storeSettings['contact_phone']))
                            <li><a href="tel:{{ $storeSettings['contact_phone'] }}">Call Us</a></li>
                        @endif
                    </ul>
                </div>
                <div>
                    <h4>Legal</h4>
                    <ul>
                        <li><a href="{{ route('privacy-policy') }}">Privacy Policy</a></li>
                        <li><a href="{{ route('terms') }}">Terms & Conditions</a></li>
                        <li><a href="{{ route('refund-policy') }}">Refund Policy</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>{{ $storeSettings['copyright_text'] ?? '© ' . date('Y') . ' ' . ($storeSettings['store_name'] ?? '') . '. All rights reserved.' }}</p>
                <div class="footer-legal">
                    <a href="{{ route('privacy-policy') }}">Privacy</a>
                    <a href="{{ route('terms') }}">Terms</a>
                    <a href="{{ route('refund-policy') }}">Refund</a>
                </div>
                <p style="margin-top:1.25rem; display:flex; align-items:center; justify-content:center; gap:.5rem; flex-wrap:wrap; color:#5b6b8c;">
                    <span>Developed by</span>
                    <a href="https://wa.me/8801930119616" target="_blank" rel="noopener" style="color:var(--primary); font-weight:700;">Zahidul Islam</a>
                    <span style="color:#3a4a6e;">|</span>
                    <a href="https://wa.me/8801930119616" target="_blank" rel="noopener" style="display:inline-flex; align-items:center; gap:.4rem;">
                        @include('partials.platform-icon', ['platform' => 'whatsapp', 'size' => 15])
                        <span style="color:var(--accent); font-weight:600;">WhatsApp: 01930-119616</span>
                    </a>
                </p>
            </div>
        </div>
    </footer>

    <button class="support-btn" id="supportBtn" aria-label="Support">
        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
    </button>

    <div class="modal-overlay" id="supportModal">
        <div class="modal-box">
            <div class="modal-header">
                <h3>Get Support</h3>
                <button class="modal-close" id="closeModal">&times;</button>
            </div>
            <div class="support-channels">
                @if($socialLinks && $socialLinks->count())
                    @foreach($socialLinks as $link)
                        @if($link->is_active)
                            <a href="{{ $link->url }}" target="_blank" rel="noopener" class="support-channel">
                                <div class="ch-icon">@include('partials.platform-icon', ['platform' => $link->platform, 'size' => 22])</div>
                                <div>
                                    <div class="ch-info">{{ $link->label }}</div>
                                    <div class="ch-sub">Chat with us on {{ $link->platform }}</div>
                                </div>
                            </a>
                        @endif
                    @endforeach
                @else
                    <p style="text-align:center;color:var(--gray-400);padding:1rem 0;">No support channels available.</p>
                @endif
            </div>
        </div>
    </div>

    <script>
    (function(){
        var h=document.getElementById('hamburgerBtn'),m=document.getElementById('mobileMenu'),open=false;
        h&&h.addEventListener('click',function(){open=!open;m.classList.toggle('open',open);});
        var sb=document.getElementById('supportBtn'),sm=document.getElementById('supportModal'),cm=document.getElementById('closeModal');
        sb&&sb.addEventListener('click',function(){sm.classList.add('open');});
        cm&&cm.addEventListener('click',function(){sm.classList.remove('open');});
        sm&&sm.addEventListener('click',function(e){if(e.target===sm)sm.classList.remove('open');});
        document.querySelectorAll('a[href^="#"]').forEach(function(a){
            a.addEventListener('click',function(e){
                var id=this.getAttribute('href');if(id.length<2)return;
                var t=document.querySelector(id);
                if(t){e.preventDefault();t.scrollIntoView({behavior:'smooth',block:'start'});if(open){open=false;m.classList.remove('open');}}
            });
        });
    })();
    </script>
    @yield('scripts')
</body>
</html>
