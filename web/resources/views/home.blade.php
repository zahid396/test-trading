@extends('layouts.app')
@section('head')
<style>
/* ── Banner Carousel (dark) ── */
.hero { position: relative; overflow: hidden; background: linear-gradient(135deg, #070b16 0%, #0a1226 45%, #0e1b33 100%); color: #f4f7fc; }
.hero-track { display: flex; transition: transform .5s cubic-bezier(.4,0,.2,1); }
.hero-slide { min-width: 100%; position: relative; }
.hero-bg { position: absolute; inset: 0; overflow: hidden; z-index: 0; }
.hero-bg img { width: 100%; height: 100%; object-fit: cover; filter: blur(24px) saturate(1.2); transform: scale(1.25); opacity: .4; }
.hero-bg::after { content: ''; position: absolute; inset: 0; background: linear-gradient(135deg, rgba(7,11,22,.92), rgba(10,18,38,.84) 50%, rgba(14,27,51,.8)); }
.hero-slide-inner { display: flex; align-items: center; min-height: 420px; padding: 3rem 0; position: relative; z-index: 1; }
.hero-slide .container { display: grid; grid-template-columns: minmax(0, 1fr) minmax(260px, 420px); align-items: center; column-gap: 3rem; row-gap: 1.5rem; }
.hero-text { z-index: 2; grid-column: 1; grid-row: 1; align-self: center; }
.hero-text h1 { font-size: 2.5rem; font-weight: 800; line-height: 1.15; margin-bottom: 1rem; color: #f4f7fc; }
.hero-text p { font-size: 1.1rem; color: rgba(244,247,252,.72); margin-bottom: 1.5rem; max-width: 520px; line-height: 1.7; }
.hero-cta { display: inline-flex; align-items: center; gap: .5rem; padding: .85rem 2rem; background: linear-gradient(135deg, #f0b90b, #e2a008); color: #0b0f1c; font-weight: 700; border-radius: 50px; font-size: 1rem; transition: var(--transition); border: none; cursor: pointer; text-decoration: none; grid-column: 1; grid-row: 2; justify-self: start; align-self: start; box-shadow: 0 6px 20px rgba(240,185,11,.28); }
.hero-cta:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(240,185,11,.4); color: #0b0f1c; }
.hero-img { position: relative; z-index: 2; grid-column: 2; grid-row: 1 / span 2; justify-self: center; align-self: center; width: 100%; max-width: 420px; text-align: center; }
.hero-img img { width: auto; height: auto; max-width: 100%; max-height: 420px; object-fit: contain; display: inline-block; border-radius: var(--radius-xl); box-shadow: var(--shadow-xl); border: 1px solid #223050; background: rgba(7,11,22,.55); }
.hero-cover-link { grid-column: 1 / -1; display: grid; grid-template-columns: minmax(0, 1fr) minmax(260px, 420px); align-items: center; column-gap: 3rem; row-gap: 1.5rem; text-decoration: none; color: inherit; }
.hero-cover-link:hover, .hero-cover-link:focus { color: inherit; }
.hero-cover-link .hero-img { grid-column: 2; }
.hero-cover-link .hero-cta { cursor: pointer; }
.video-frame { position: relative; width: 100%; aspect-ratio: 16/9; overflow: hidden; border-radius: var(--radius-xl); border: 1px solid #223050; box-shadow: var(--shadow-xl); background: #000; cursor: pointer; }
.video-frame .video-poster { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; display: block; transition: opacity .3s ease; }
.video-frame .video-playbtn { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 64px; height: 44px; background: rgba(20,20,20,.78); border: none; border-radius: 10px; color: #fff; font-size: 1.05rem; cursor: pointer; display: flex; align-items: center; justify-content: center; padding-left: 3px; transition: var(--transition); box-shadow: 0 4px 18px rgba(0,0,0,.5); }
.video-frame .video-playbtn:hover { background: linear-gradient(135deg, #f0b90b, #e2a008); color: #0b0f1c; transform: translate(-50%, -50%) scale(1.08); }
.video-frame iframe { position: absolute; inset: 0; width: 100%; height: 100%; border: 0; display: block; }
.hero-nav { position: absolute; bottom: 1.5rem; left: 50%; transform: translateX(-50%); display: flex; gap: .5rem; z-index: 10; }
.hero-dot { width: 10px; height: 10px; border-radius: 50%; border: 2px solid rgba(244,247,252,.55); background: transparent; cursor: pointer; transition: var(--transition); }
.hero-dot.active { background: var(--primary); border-color: var(--primary); }
.hero-arrow { position: absolute; top: 50%; transform: translateY(-50%); z-index: 10; width: 42px; height: 42px; border-radius: 50%; background: rgba(244,247,252,.15); backdrop-filter: blur(4px); border: 1px solid rgba(244,247,252,.25); color: #f4f7fc; font-size: 1.1rem; cursor: pointer; transition: var(--transition); display: flex; align-items: center; justify-content: center; }
.hero-arrow:hover { background: rgba(240,185,11,.25); border-color: rgba(240,185,11,.5); }
.hero-arrow.prev { left: 1.5rem; }
.hero-arrow.next { right: 1.5rem; }

/* ── Trading Hero (static) ── */
.trade-hero { position: relative; overflow: hidden; background: linear-gradient(135deg, #070b16 0%, #0b1226 45%, #0d1a33 100%); color: #f4f7fc; padding: 5.5rem 0 5rem; }
.trade-hero::before { content: ''; position: absolute; inset: 0; pointer-events: none; background:
    radial-gradient(620px 320px at 12% 8%, rgba(240,185,11,.14), transparent 62%),
    radial-gradient(720px 420px at 88% 92%, rgba(22,199,132,.12), transparent 60%);
}
.trade-hero-grid { position: absolute; inset: 0; opacity: .5; pointer-events: none;
    background-image: linear-gradient(rgba(148,163,184,.07) 1px, transparent 1px), linear-gradient(90deg, rgba(148,163,184,.07) 1px, transparent 1px);
    background-size: 44px 44px;
    -webkit-mask-image: radial-gradient(85% 85% at 50% 15%, #000 35%, transparent 100%);
    mask-image: radial-gradient(85% 85% at 50% 15%, #000 35%, transparent 100%);
}
.trade-hero .container { position: relative; z-index: 2; display: grid; grid-template-columns: minmax(0, 1.1fr) minmax(300px, 460px); gap: 3.5rem; align-items: center; }
.trade-badge { display: inline-flex; align-items: center; gap: .55rem; padding: .4rem .95rem; border-radius: 50px; font-size: .76rem; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; color: var(--primary); background: var(--primary-50); border: 1px solid rgba(240,185,11,.32); margin-bottom: 1.2rem; }
.trade-badge .dot { width: 8px; height: 8px; border-radius: 50%; background: var(--accent); box-shadow: 0 0 0 4px rgba(22,199,132,.15); animation: blink 2s infinite; }
@keyframes blink { 0%, 100% { opacity: 1; } 50% { opacity: .3; } }
.trade-hero h1 { font-size: 3.05rem; font-weight: 800; line-height: 1.12; margin-bottom: 1.1rem; color: #f4f7fc; }
.trade-hero h1 .hl { background: linear-gradient(120deg, #f0b90b 0%, #fcd34d 100%); -webkit-background-clip: text; background-clip: text; color: transparent; }
.trade-hero p { font-size: 1.12rem; color: rgba(244,247,252,.72); max-width: 560px; line-height: 1.75; margin-bottom: 1.8rem; }
.trade-ctas { display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 2.2rem; }
.btn-gold { display: inline-flex; align-items: center; gap: .5rem; padding: .9rem 1.9rem; border-radius: 50px; background: linear-gradient(135deg, #f0b90b, #e2a008); color: #0b0f1c; font-family: var(--font-display); font-weight: 700; font-size: .98rem; transition: var(--transition); box-shadow: 0 6px 20px rgba(240,185,11,.28); }
.btn-gold:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(240,185,11,.42); color: #0b0f1c; }
.btn-ghost { display: inline-flex; align-items: center; gap: .5rem; padding: .9rem 1.9rem; border-radius: 50px; border: 1px solid #2c3a5e; color: #dde5f2; font-family: var(--font-display); font-weight: 600; font-size: .98rem; transition: var(--transition); background: transparent; }
.btn-ghost:hover { border-color: var(--primary); color: var(--primary); background: var(--primary-50); }
.trade-stats { display: flex; gap: 2.4rem; flex-wrap: wrap; }
.trade-stat { display: flex; flex-direction: column; gap: .15rem; }
.trade-stat b { font-family: var(--font-display); font-size: 1.45rem; font-weight: 800; color: var(--primary); }
.trade-stat span { font-size: .82rem; color: var(--gray-500); letter-spacing: .03em; }
.chart-card { position: relative; z-index: 2; background: linear-gradient(180deg, #141e36, #10182a); border: 1px solid #223050; border-radius: var(--radius-xl); padding: 1.15rem 1.25rem 1.25rem; box-shadow: var(--shadow-xl), 0 24px 60px rgba(0,0,0,.5); }
.chart-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: .9rem; }
.chart-head .pair { font-family: var(--font-display); font-weight: 700; font-size: .95rem; color: #f4f7fc; letter-spacing: .02em; }
.chart-head .price { text-align: right; line-height: 1.25; }
.chart-head .price b { display: block; font-size: 1.05rem; color: var(--up); font-family: var(--font-display); }
.chart-head .price span { font-size: .75rem; color: var(--up); font-weight: 700; }
.chart-head .price span.down { color: var(--down); }
.chart-svg { width: 100%; height: auto; display: block; }
.chart-tag { display: inline-flex; align-items: center; gap: .4rem; margin-top: .85rem; padding: .35rem .8rem; font-size: .72rem; font-weight: 600; color: var(--accent); background: rgba(22,199,132,.1); border: 1px solid rgba(22,199,132,.28); border-radius: 50px; }
.chart-card-img { display: flex; align-items: center; justify-content: center; padding: .5rem; }
.chart-card-img img { width: 100%; height: 100%; aspect-ratio: 420/272; object-fit: cover; border-radius: calc(var(--radius-xl) - .5rem); }

/* ── Sections ── */
.section { padding: 4rem 0; }
.section-header { text-align: center; margin-bottom: 2.5rem; }
.section-header h2 { font-size: 1.75rem; font-weight: 800; color: var(--gray-900); }
.section-header p { color: var(--gray-500); margin-top: .5rem; font-size: 1rem; }

/* ── Product Cards ── */
.pcard-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 1.25rem; }
.pcard-scroll { display: flex; gap: 1.25rem; overflow-x: auto; scroll-snap-type: x mandatory; -webkit-overflow-scrolling: touch; padding-bottom: .5rem; }
.pcard-scroll::-webkit-scrollbar { height: 4px; }
.pcard-scroll::-webkit-scrollbar-track { background: var(--gray-100); border-radius: 4px; }
.pcard-scroll::-webkit-scrollbar-thumb { background: var(--primary-light); border-radius: 4px; }
.pcard-scroll .pcard { min-width: 260px; max-width: 260px; scroll-snap-align: start; flex-shrink: 0; }
.pcard { background: var(--white); border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow); transition: var(--transition); cursor: pointer; border: 1px solid var(--gray-100); }
.pcard:hover { transform: translateY(-4px); box-shadow: var(--shadow-lg); }
.pcard-img { position: relative; aspect-ratio: 4/3; overflow: hidden; background: var(--gray-100); }
.pcard-img img { width: 100%; height: 100%; object-fit: cover; transition: transform .4s ease; }
.pcard:hover .pcard-img img { transform: scale(1.04); }
.pcard-placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: var(--gray-100); }
.pcard-badge { position: absolute; top: .75rem; left: .75rem; background: var(--down); color: #fff; font-size: .75rem; font-weight: 700; padding: .25rem .65rem; border-radius: 50px; }
.pcard-unavailable { position: absolute; top: .75rem; right: .75rem; background: rgba(0,0,0,.55); color: #dde5f2; font-size: .72rem; font-weight: 600; padding: .25rem .65rem; border-radius: 50px; border: 1px solid rgba(255,255,255,.25); }
.pcard-body { padding: 1rem 1.15rem 1.25rem; }
.pcard-title { font-size: 1rem; font-weight: 700; color: var(--gray-900); margin-bottom: .25rem; line-height: 1.35; }
.pcard-subtitle { font-size: .82rem; color: var(--gray-500); margin-bottom: .65rem; line-height: 1.5; }
.pcard-price { display: flex; align-items: baseline; gap: .5rem; margin-bottom: .85rem; }
.pcard-current { font-size: 1.1rem; font-weight: 800; color: var(--primary); }
.pcard-old { font-size: .85rem; color: var(--gray-400); text-decoration: line-through; }
.pcard-btn { width: 100%; padding: .6rem; background: var(--primary-50); color: var(--primary); font-weight: 600; font-size: .88rem; border: 1px solid rgba(240,185,11,.35); border-radius: var(--radius); cursor: pointer; transition: var(--transition); }
.pcard-btn:hover { background: linear-gradient(135deg, #f0b90b, #e2a008); color: #0b0f1c; border-color: transparent; }
.pcard-btn:disabled { background: var(--gray-100); color: var(--gray-400); cursor: not-allowed; border-color: transparent; }

/* ── Reviews ── */
.review-carousel { max-width: 700px; margin: 0 auto; position: relative; min-height: 200px; }
.review-card { background: var(--white); border-radius: var(--radius-lg); padding: 2rem; box-shadow: var(--shadow-md); text-align: center; border: 1px solid var(--gray-100); position: relative; }
.review-card-inner { animation: fadeUp .4s ease; }
@keyframes fadeUp { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }
.review-stars { color: #f59e0b; font-size: 1.15rem; margin-bottom: .75rem; letter-spacing: 2px; }
.review-text { font-size: 1rem; color: var(--gray-700); line-height: 1.75; margin-bottom: 1rem; font-style: italic; }
.review-author { font-weight: 700; color: var(--gray-900); font-size: .95rem; }
.review-nav { display: flex; justify-content: center; gap: .5rem; margin-top: 1.5rem; }
.review-dot { width: 8px; height: 8px; border-radius: 50%; border: none; background: var(--gray-300); cursor: pointer; transition: var(--transition); }
.review-dot.active { background: var(--primary); width: 24px; border-radius: 4px; }

/* ── About ── */
.about-section { background: var(--white); border-radius: var(--radius-xl); padding: 3rem 2.5rem; max-width: 800px; margin: 0 auto; text-align: center; box-shadow: var(--shadow); border: 1px solid var(--gray-100); }
.about-section h2 { font-size: 1.6rem; font-weight: 800; color: var(--gray-900); margin-bottom: 1rem; }
.about-section p { color: var(--gray-600); line-height: 1.8; font-size: 1rem; max-width: 600px; margin: 0 auto; }
.about-contact { display: flex; justify-content: center; gap: 2rem; margin-top: 1.5rem; flex-wrap: wrap; }
.about-contact-item { display: flex; align-items: center; gap: .5rem; color: var(--gray-600); font-size: .9rem; }
.about-contact-item strong { color: var(--gray-800); }
.view-all-link { display: inline-flex; align-items: center; gap: .4rem; color: var(--primary); font-weight: 600; font-size: .95rem; margin-top: 1rem; }
.view-all-link:hover { gap: .6rem; }

/* ── Product Modal ── */
#productModal.modal-overlay { display: none; position: fixed; inset: 0; background: rgba(2,6,17,.72); z-index: 300; align-items: center; justify-content: center; backdrop-filter: blur(6px); }
#productModal.modal-overlay.open { display: flex; animation: fadeIn .2s ease; }
#productModal .modal-box { background: var(--white); border-radius: var(--radius-xl); max-width: 720px; width: 92%; max-height: 88vh; overflow-y: auto; box-shadow: var(--shadow-xl); animation: modalUp .25s ease; border: 1px solid #223050; }
#productModal .modal-close { position: absolute; top: 1rem; right: 1rem; background: rgba(0,0,0,.35); border: none; width: 36px; height: 36px; border-radius: 50%; font-size: 1.2rem; cursor: pointer; color: var(--gray-500); display: flex; align-items: center; justify-content: center; transition: var(--transition); z-index: 5; }
#productModal .modal-close:hover { background: rgba(240,185,11,.25); color: var(--primary); }
#productModal .modal-img { width: 100%; aspect-ratio: 16/9; object-fit: cover; border-radius: var(--radius-xl) var(--radius-xl) 0 0; }
#productModal .modal-body { padding: 1.75rem; }
#productModal .modal-title { font-size: 1.35rem; font-weight: 800; color: var(--gray-900); margin-bottom: .25rem; }
#productModal .modal-subtitle { color: var(--gray-500); font-size: .9rem; margin-bottom: 1rem; }
#productModal .modal-price-row { display: flex; align-items: baseline; gap: .75rem; margin-bottom: 1rem; }
#productModal .modal-price { font-size: 1.4rem; font-weight: 800; color: var(--primary); }
#productModal .modal-old-price { font-size: 1rem; color: var(--gray-400); text-decoration: line-through; }
#productModal .modal-discount { background: rgba(246,70,93,.14); color: #f6465d; font-size: .78rem; font-weight: 700; padding: .2rem .6rem; border-radius: 50px; }
#productModal .modal-desc { color: var(--gray-600); line-height: 1.75; font-size: .95rem; margin-bottom: 1.25rem; }
#productModal .modal-features { margin-bottom: 1.25rem; }
#productModal .modal-features h4 { font-size: .95rem; font-weight: 700; color: var(--gray-800); margin-bottom: .5rem; }
#productModal .modal-features ul { list-style: none; }
#productModal .modal-features li { padding: .3rem 0; font-size: .9rem; color: var(--gray-600); display: flex; align-items: flex-start; gap: .5rem; }
#productModal .modal-features li::before { content: '✓'; color: var(--accent); font-weight: 700; flex-shrink: 0; }
#productModal .modal-buy { display: block; width: 100%; padding: .85rem; background: linear-gradient(135deg, #f0b90b, #e2a008); color: #0b0f1c; font-family: var(--font-display); font-weight: 700; font-size: 1rem; border: none; border-radius: var(--radius); cursor: pointer; text-align: center; transition: var(--transition); text-decoration: none; }
#productModal .modal-buy:hover { opacity: .92; transform: translateY(-1px); color: #0b0f1c; }
#productModal .modal-loading { display: flex; align-items: center; justify-content: center; padding: 3rem; color: var(--gray-400); }
#productModal .modal-loading::after { content: ''; width: 28px; height: 28px; border: 3px solid var(--gray-200); border-top-color: var(--primary); border-radius: 50%; animation: spin .6s linear infinite; margin-left: .75rem; }
@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
@keyframes modalUp { from { opacity: 0; transform: translateY(20px) scale(.96); } to { opacity: 1; transform: translateY(0) scale(1); } }
@keyframes spin { to { transform: rotate(360deg); } }

/* ── Hero & Trading Hero Responsive ── */
@media (max-width: 768px) {
    .hero-slide-inner { min-height: 340px; }
    .hero-slide .container { grid-template-columns: 1fr; text-align: center; gap: 1.25rem; }
    .hero-text h1 { font-size: 1.7rem; }
    .hero-text { grid-row: auto; order: 1; }
    .hero-text p { margin-left: auto; margin-right: auto; }
    .hero-cta { grid-row: auto; order: 3; justify-self: center; }
    .hero-img { grid-row: auto; order: 2; flex: none; max-width: 280px; width: 100%; }
    .hero-img img { max-height: 320px; }
    .hero-cover-link { grid-template-columns: 1fr; text-align: center; }
    .hero-cover-link .hero-text { order: 1; }
    .hero-cover-link .hero-img { grid-column: 1; order: 2; justify-self: center; }
    .hero-cover-link .hero-cta { grid-column: 1; order: 3; justify-self: center; }
    .hero-video, .video-frame { max-width: 460px; margin: 0 auto; }
    .hero-arrow { display: none; }
    .trade-hero { padding: 3.5rem 0 3rem; text-align: center; }
    .trade-hero .container { grid-template-columns: 1fr; gap: 2.5rem; }
    .trade-hero h1 { font-size: 2.1rem; }
    .trade-hero p { margin-left: auto; margin-right: auto; }
    .trade-ctas { justify-content: center; }
    .trade-stats { justify-content: center; gap: 1.6rem; }
    .chart-card { max-width: 420px; margin: 0 auto; }
    .pcard-grid { grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); }
    .section { padding: 2.5rem 0; }
    .about-section { padding: 2rem 1.25rem; }
}
</style>
@endsection

@section('content')

@if(isset($banners) && $banners->count())
<section class="hero" id="heroCarousel">
    <div class="hero-track" id="heroTrack">
        @foreach($banners as $banner)
        @php
            $bannerVid = $banner->isVideo() ? $banner->youtubeId() : null;
            $bannerClick = !$bannerVid ? $banner->actionUrl() : null;
        @endphp
        <div class="hero-slide">
            <div class="hero-bg">
                @if(!empty($banner->image))
                    <img src="{{ asset('storage/banners/' . $banner->image) }}" alt="">
                @endif
            </div>
            <div class="hero-slide-inner">
                <div class="container">
                    @if($bannerVid)
                        <div class="hero-text">
                            @if(!empty($banner->title))
                                <h1>{{ $banner->title }}</h1>
                            @endif
                            @if(!empty($banner->subtitle))
                                <p>{{ $banner->subtitle }}</p>
                            @endif
                            @if(!empty($banner->button_text) && !empty($banner->actionUrl()))
                                <a href="{{ $banner->actionUrl() }}" class="hero-cta">{{ $banner->button_text }} →</a>
                            @endif
                        </div>
                        <div class="hero-img hero-video" style="max-width:560px;">
                            <div class="video-frame" data-video="{{ $bannerVid }}">
                                <img class="video-poster" src="https://i.ytimg.com/vi/{{ $bannerVid }}/hqdefault.jpg" alt="{{ $banner->title ?? 'Banner video' }}">
                                <button type="button" class="video-playbtn" aria-label="Play video">▶</button>
                            </div>
                        </div>
                    @elseif($bannerClick)
                        <a href="{{ $bannerClick }}" class="hero-cover-link" {{ $banner->action_type === 'external_url' ? 'target="_blank" rel="noopener"' : '' }}>
                            <div class="hero-text">
                                @if(!empty($banner->title))
                                    <h1>{{ $banner->title }}</h1>
                                @endif
                                @if(!empty($banner->subtitle))
                                    <p>{{ $banner->subtitle }}</p>
                                @endif
                            </div>
                            @if(!empty($banner->image))
                            <div class="hero-img">
                                <img src="{{ asset('storage/banners/' . $banner->image) }}" alt="{{ $banner->title }}">
                            </div>
                            @endif
                            @if(!empty($banner->button_text))
                                <span class="hero-cta">{{ $banner->button_text }} →</span>
                            @endif
                        </a>
                    @else
                        <div class="hero-text">
                            @if(!empty($banner->title))
                                <h1>{{ $banner->title }}</h1>
                            @endif
                            @if(!empty($banner->subtitle))
                                <p>{{ $banner->subtitle }}</p>
                            @endif
                        </div>
                        @if(!empty($banner->image))
                        <div class="hero-img">
                            <img src="{{ asset('storage/banners/' . $banner->image) }}" alt="{{ $banner->title }}">
                        </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <button class="hero-arrow prev" id="heroPrev">‹</button>
    <button class="hero-arrow next" id="heroNext">›</button>
    <div class="hero-nav" id="heroNav">
        @foreach($banners as $i => $banner)
            <button class="hero-dot {{ $i===0?'active':'' }}" data-slide="{{ $i }}"></button>
        @endforeach
    </div>
</section>
@endif

<section class="trade-hero" id="homeHero">
    @php
        $heroBadge  = !empty($storeSettings['hero_badge'])  ? $storeSettings['hero_badge']  : 'Trading Courses &amp; Careers';
        $heroTitle  = !empty($storeSettings['hero_title'])  ? $storeSettings['hero_title']  : 'Master the Markets. Trade with';
        $heroHl     = !empty($storeSettings['hero_title_highlight']) ? $storeSettings['hero_title_highlight'] : 'Confidence.';
        $heroSub    = !empty($storeSettings['hero_subtitle']) ? $storeSettings['hero_subtitle'] : 'Premium trading courses, expert mentorship and job placement support — built for traders who want real, lasting results in stocks, forex and crypto.';
        $cta1Text   = !empty($storeSettings['hero_cta1_text']) ? $storeSettings['hero_cta1_text'] : 'Explore Courses';
        $cta1Url    = !empty($storeSettings['hero_cta1_url'])  ? $storeSettings['hero_cta1_url']  : route('products.index');
        $cta2Text   = !empty($storeSettings['hero_cta2_text']) ? $storeSettings['hero_cta2_text'] : 'Track Your Order';
        $cta2Url    = !empty($storeSettings['hero_cta2_url'])  ? $storeSettings['hero_cta2_url']  : route('order.track');
        $stats = [
            ['value' => $storeSettings['hero_stat1_value'] ?? '', 'label' => $storeSettings['hero_stat1_label'] ?? ''],
            ['value' => $storeSettings['hero_stat2_value'] ?? '', 'label' => $storeSettings['hero_stat2_label'] ?? ''],
            ['value' => $storeSettings['hero_stat3_value'] ?? '', 'label' => $storeSettings['hero_stat3_label'] ?? ''],
        ];
        $statDefaults = [
            ['value' => '500+', 'label' => 'Students Trained'],
            ['value' => '30+', 'label' => 'Course Modules'],
            ['value' => 'Job &amp;', 'label' => 'Placement Support'],
        ];
    @endphp
    <div class="trade-hero-grid" aria-hidden="true"></div>
    <div class="container">
        <div>
            <span class="trade-badge"><span class="dot"></span> {!! $heroBadge !!}</span>
            <h1>{!! nl2br(e($heroTitle)) !!} @if(!empty($heroHl))<span class="hl">{{ $heroHl }}</span>@endif</h1>
            <p>{!! nl2br(e($heroSub)) !!}</p>
            <div class="trade-ctas">
                <a href="{{ $cta1Url }}" class="btn-gold">{{ $cta1Text }} →</a>
                <a href="{{ $cta2Url }}" class="btn-ghost">{{ $cta2Text }}</a>
            </div>
            <div class="trade-stats">
                @foreach($stats as $i => $stat)
                    @if(!empty($stat['value']) || !empty($stat['label']))
                        <div class="trade-stat"><b>{{ $stat['value'] }}</b><span>{{ $stat['label'] }}</span></div>
                    @else
                        <div class="trade-stat"><b>{{ $statDefaults[$i]['value'] }}</b><span>{{ $statDefaults[$i]['label'] }}</span></div>
                    @endif
                @endforeach
            </div>
        </div>
        @if(!empty($storeSettings['hero_image']))
        <div class="chart-card chart-card-img">
            <img src="{{ asset('storage/store/' . $storeSettings['hero_image']) }}" alt="{{ $heroBadge }}">
        </div>
        @else
        <div class="chart-card">
            <div class="chart-head">
                <span class="pair">GOLD/USD · 1H</span>
                <div class="price"><b>$2,348.60</b><span>▲ 0.62%</span></div>
            </div>
            <svg class="chart-svg" viewBox="0 0 420 190" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <defs>
                    <linearGradient id="areaGold" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#f0b90b" stop-opacity=".35"/>
                        <stop offset="100%" stop-color="#f0b90b" stop-opacity="0"/>
                    </linearGradient>
                </defs>
                <g stroke="#223050" stroke-width="1">
                    <line x1="0" y1="38" x2="420" y2="38"/><line x1="0" y1="76" x2="420" y2="76"/>
                    <line x1="0" y1="114" x2="420" y2="114"/><line x1="0" y1="152" x2="420" y2="152"/>
                    <line x1="84" y1="0" x2="84" y2="190"/><line x1="168" y1="0" x2="168" y2="190"/>
                    <line x1="252" y1="0" x2="252" y2="190"/><line x1="336" y1="0" x2="336" y2="190"/>
                </g>
                <path d="M0 150 L52 142 L84 148 L126 120 L164 132 L204 96 L244 108 L282 74 L318 88 L360 46 L420 60 L420 190 L0 190 Z" fill="url(#areaGold)"/>
                <path d="M0 150 L52 142 L84 148 L126 120 L164 132 L204 96 L244 108 L282 74 L318 88 L360 46 L420 60" stroke="#f0b90b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                <circle cx="360" cy="46" r="4" fill="#f0b90b"/>
                <g stroke="#16c784" stroke-width="2">
                    <rect x="20" y="112" width="10" height="38" fill="#16c784" stroke="none"/>
                    <rect x="52" y="100" width="10" height="42" fill="#16c784" stroke="none"/>
                    <rect x="105" y="86" width="10" height="56" fill="#16c784" stroke="none"/>
                    <rect x="140" y="118" width="10" height="44" fill="#f6465d" stroke="none"/>
                    <rect x="180" y="70" width="10" height="60" fill="#16c784" stroke="none"/>
                    <rect x="220" y="92" width="10" height="50" fill="#16c784" stroke="none"/>
                    <rect x="265" y="50" width="10" height="72" fill="#16c784" stroke="none"/>
                    <rect x="300" y="82" width="10" height="60" fill="#f6465d" stroke="none"/>
                    <rect x="340" y="34" width="10" height="76" fill="#16c784" stroke="none"/>
                </g>
            </svg>
            <span class="chart-tag">Live market simulation · Candles &amp; trend</span>
        </div>
        @endif
    </div>
</section>

<div class="ticker" aria-hidden="true">
    <div class="ticker-track">
        @php
            $ticks = [
                ['FOREX · EUR/USD', 1.0824, 'down', -0.18], ['CRYPTO · BTC/USD', 68240.0, 'up', 2.41],
                ['METALS · GOLD', 2348.6, 'up', 0.62], ['INDICES · S&P 500', 5121.7, 'up', 0.32],
                ['FOREX · GBP/USD', 1.2745, 'up', 0.24], ['CRYPTO · ETH/USD', 3241.5, 'up', 1.86],
                ['INDICES · NASDAQ', 17986.0, 'down', -0.41], ['METALS · SILVER', 27.84, 'down', -0.35],
                ['FOREX · USD/BDT', 118.42, 'up', 0.08], ['CRYPTO · BNB/USD', 587.9, 'down', -0.52],
                ['INDICES · DOW JONES', 38210.0, 'up', 0.18], ['CRYPTO · SOL/USD', 142.3, 'up', 3.04],
            ];
        @endphp
        @foreach(array_merge($ticks, $ticks) as $t)
        <span class="ticker-item"><b>{{ $t[0] }}</b><span class="val">{{ $t[1] < 1000 ? number_format($t[1], 2) : number_format($t[1], 0) }}</span><span class="{{ $t[2] === 'up' ? 'up' : 'down' }}">{{ $t[2] === 'up' ? '▲' : '▼' }} {{ number_format(abs($t[3]), 2) }}%</span></span>
        @endforeach
    </div>
</div>

@if(isset($products) && $products->count())
<section class="section">
    <div class="container">
        <div class="section-header">
            <h2>Featured Courses &amp; Programs</h2>
            <p>Discover our curated collection of premium trading courses</p>
        </div>
        <div class="pcard-scroll">
            @foreach($products as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
        <div style="text-align:center;margin-top:1.5rem;">
            <a href="{{ route('products.index') }}" class="view-all-link">View All Products →</a>
        </div>
    </div>
</section>
@endif

@if(isset($reviews) && $reviews->count())
<section class="section" id="reviews" style="background:var(--gray-100);">
    <div class="container">
        <div class="section-header">
            <h2>What Our Customers Say</h2>
            <p>Real reviews from real customers</p>
        </div>
        <div class="review-carousel" id="reviewCarousel">
            @foreach($reviews as $ri => $review)
            <div class="review-card" style="display:{{ $ri===0?'block':'none' }}" data-review="{{ $ri }}">
                <div class="review-card-inner">
                    <div class="review-stars">{{ str_repeat('★', $review->rating ?? 5) }}{{ str_repeat('☆', max(0, 5 - ($review->rating ?? 5))) }}</div>
                    <p class="review-text">"{{ $review->review_text ?? '' }}"</p>
                    <div class="review-author">{{ $review->customer_name ?? 'Customer' }}</div>
                </div>
            </div>
            @endforeach
            <div class="review-nav" id="reviewNav">
                @foreach($reviews as $i => $r)
                    <button class="review-dot {{ $i===0?'active':'' }}" data-idx="{{ $i }}"></button>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

@if(!empty($storeSettings['about_text']))
<section class="section" id="about">
    <div class="container">
        <div class="about-section">
            <h2>About {{ $storeSettings['store_name'] ?? 'Us' }}</h2>
            <p>{!! nl2br(e($storeSettings['about_text'])) !!}</p>
            <div class="about-contact">
                @if(!empty($storeSettings['contact_email']))
                    <div class="about-contact-item"><strong>Email:</strong> {{ $storeSettings['contact_email'] }}</div>
                @endif
                @if(!empty($storeSettings['contact_phone']))
                    <div class="about-contact-item"><strong>Phone:</strong> {{ $storeSettings['contact_phone'] }}</div>
                @endif
            </div>
        </div>
    </div>
</section>
@endif

@include('partials.product-modal')

@endsection

@section('scripts')
<script>
(function(){
    /* Hero Carousel */
    var track=document.getElementById('heroTrack'), dots=document.querySelectorAll('.hero-dot'),
        prev=document.getElementById('heroPrev'), next=document.getElementById('heroNext');
    if(!track) return;
    var slides=track.children.length, cur=0, auto;
    function go(i){ cur=((i%slides)+slides)%slides; track.style.transform='translateX(-'+cur*100+'%)'; dots.forEach(function(d,j){d.classList.toggle('active',j===cur);}); }
    dots.forEach(function(d){d.addEventListener('click',function(){go(+d.dataset.slide);resetAuto();});});
    if(prev) prev.addEventListener('click',function(){go(cur-1);resetAuto();});
    if(next) next.addEventListener('click',function(){go(cur+1);resetAuto();});
    function startAuto(){auto=setInterval(function(){go(cur+1);},5000);}
    function resetAuto(){clearInterval(auto);startAuto();}
    startAuto();
    var hx=0;
    track.addEventListener('touchstart',function(e){hx=e.touches[0].clientX;},{passive:true});
    track.addEventListener('touchend',function(e){var dx=e.changedTouches[0].clientX-hx;if(Math.abs(dx)>50){dx>0?go(cur-1):go(cur+1);resetAuto();}});

    /* Review Carousel */
    var cards=document.querySelectorAll('.review-card'),rdots=document.querySelectorAll('.review-dot'),ri=0,rt;
    function goReview(j){ri=((j%cards.length)+cards.length)%cards.length;cards.forEach(function(c,k){c.style.display=k===ri?'block':'none';});rdots.forEach(function(d,k){d.classList.toggle('active',k===ri);});}
    rdots.forEach(function(d){d.addEventListener('click',function(){goReview(+d.dataset.idx);clearInterval(rt);rt=setInterval(function(){goReview(ri+1);},6000);});});
    if(cards.length>1) rt=setInterval(function(){goReview(ri+1);},6000);

    /* Banner Video: load the player only when the visitor clicks play */
    document.querySelectorAll('.video-frame').forEach(function(frame){
        var vid = frame.getAttribute('data-video');
        if(!vid) return;
        frame.addEventListener('click', function play(){
            frame.removeEventListener('click', play);
            var iframe = document.createElement('iframe');
            iframe.src = 'https://www.youtube.com/embed/' + vid + '?autoplay=1&playsinline=1&rel=0';
            iframe.title = 'Banner video';
            iframe.setAttribute('allow', 'accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture; fullscreen');
            iframe.setAttribute('allowfullscreen', '');
            frame.innerHTML = '';
            frame.appendChild(iframe);
        });
    });
})();
</script>
@endsection
