@extends('layouts.app')
@section('head')
<style>
.products-page { padding: 2.5rem 0 4rem; }
.products-header { margin-bottom: 2rem; }
.products-header h1 { font-size: 1.75rem; font-weight: 800; color: var(--gray-900); font-family: var(--font-display); }
.products-header p { color: var(--gray-500); margin-top: .3rem; }
.products-toolbar { display: flex; gap: 1rem; margin-bottom: 2rem; flex-wrap: wrap; align-items: center; }
.search-box { flex: 1; min-width: 240px; position: relative; }
.search-box input { width: 100%; padding: .75rem 1rem .75rem 2.75rem; border: 1px solid var(--gray-200); border-radius: var(--radius); font-size: .95rem; background: var(--white); transition: var(--transition); color: var(--gray-800); color-scheme: dark; }
.search-box input:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(240,185,11,.12); background: #18223c; }
.search-box input::placeholder { color: var(--gray-400); }
.search-box .search-icon { position: absolute; left: .85rem; top: 50%; transform: translateY(-50%); color: var(--gray-400); pointer-events: none; }
.filter-select { padding: .75rem 2.5rem .75rem 1rem; border: 1px solid var(--gray-200); border-radius: var(--radius); font-size: .95rem; background: var(--white); color: var(--gray-700); cursor: pointer; appearance: none; color-scheme: dark; background-image: url("data:image/svg+xml,%3Csvg width='12' height='8' viewBox='0 0 12 8' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1.5L6 6.5L11 1.5' stroke='%23a8b6d0' stroke-width='2' stroke-linecap='round'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right .75rem center; }
.filter-select:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(240,185,11,.12); }
.products-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 1.25rem; }
.empty-state { text-align: center; padding: 4rem 1rem; }
.empty-state svg { margin: 0 auto 1rem; opacity: .4; }
.empty-state h3 { color: var(--gray-700); font-size: 1.15rem; margin-bottom: .5rem; }
.empty-state p { color: var(--gray-400); }
.pcard-scroll .pcard { min-width: auto; max-width: none; scroll-snap-align: auto; }
.pcard { background: var(--white); border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow); transition: var(--transition); cursor: pointer; border: 1px solid var(--gray-200); }
.pcard:hover { transform: translateY(-4px); box-shadow: var(--shadow-lg); border-color: rgba(240,185,11,.35); }
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
.pagination { display: flex; justify-content: center; gap: .35rem; margin-top: 2.5rem; }
.pagination a, .pagination span { display: inline-flex; align-items: center; justify-content: center; min-width: 40px; height: 40px; padding: 0 .75rem; border-radius: var(--radius); font-weight: 500; font-size: .9rem; transition: var(--transition); }
.pagination a { background: var(--white); color: var(--gray-600); border: 1px solid var(--gray-200); }
.pagination a:hover { border-color: var(--primary); color: var(--primary); background: var(--primary-50); }
.pagination .active { background: linear-gradient(135deg, #f0b90b, #e2a008); color: #0b0f1c; border: 1px solid #f0b90b; font-weight: 700; }
.pagination .disabled { color: var(--gray-300); pointer-events: none; }

/* Product modal */
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

@media (max-width: 768px) {
    .products-grid { grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); }
    .products-toolbar { flex-direction: column; align-items: stretch; }
    .search-box { min-width: 0; }
    .filter-select { width: 100%; }
    .pagination { flex-wrap: wrap; }
    #productModal .modal-box { width: 95%; max-height: 90vh; }
    #productModal .modal-body { padding: 1.25rem; }
}
</style>
@endsection

@section('content')
<div class="products-page">
    <div class="container">
        <div class="products-header">
            <h1>All Courses &amp; Programs</h1>
            <p>Browse our complete collection of premium trading courses</p>
        </div>

        <form method="GET" action="{{ route('products.index') }}">
            <div class="products-toolbar">
                <div class="search-box">
                    <svg class="search-icon" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="8" cy="8" r="6"/><path d="M13.5 13.5L17 17"/></svg>
                    <input type="text" name="search" placeholder="Search products..." value="{{ request('search') }}">
                </div>
                @if(isset($categories) && $categories->count())
                <select name="category" class="filter-select" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->slug ?? $cat->id }}" {{ request('category')==($cat->slug ?? $cat->id)?'selected':'' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                @endif
                <noscript><button type="submit" style="padding:.75rem 1.5rem;background:var(--primary);color:var(--white);border:none;border-radius:var(--radius);font-weight:600;cursor:pointer;">Search</button></noscript>
            </div>
        </form>

        @if(isset($products) && $products->count())
            <div class="products-grid">
                @foreach($products as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
            @if(method_exists($products,'links') && $products->hasPages())
                <div class="pagination">{{ $products->withQueryString()->links() }}</div>
            @endif
        @else
            <div class="empty-state">
                <svg width="64" height="64" fill="none" stroke="#9ca3af" stroke-width="1.5"><rect x="8" y="8" width="48" height="48" rx="6"/><circle cx="26" cy="26" r="8"/><path d="M8 42l14-14 10 10 14-14"/></svg>
                <h3>No products found</h3>
                <p>Try adjusting your search or filter criteria.</p>
            </div>
        @endif
    </div>
</div>

@include('partials.product-modal')
@endsection
