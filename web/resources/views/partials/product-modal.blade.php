<div class="modal-overlay" id="productModal">
    <div class="modal-box" id="productModalBox" style="position:relative;">
        <button class="modal-close" id="closeProductModal">&times;</button>
        <div id="productModalContent">
            <div class="modal-loading">Loading</div>
        </div>
    </div>
</div>
<script>
(function(){
    var pm=document.getElementById('productModal'),
        pcb=document.getElementById('productModalContent'),
        pcm=document.getElementById('closeProductModal');

    function esc(s){
        return String(s==null?'':s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#039;');
    }
    function money(n){
        return '\u09F3'+Number(n||0).toLocaleString('en-US',{minimumFractionDigits:2,maximumFractionDigits:2});
    }
    function stars(r){
        r=Math.max(1,Math.min(5,Math.round(r||5)));
        return '\u2605'.repeat(r)+'\u2606'.repeat(5-r);
    }

    function render(data){
        var p=data.product||{};
        var reviews=data.reviews||[];
        var isAvailable=(p.status||'available')==='available';
        var featuresHtml='';
        if(p.features&&p.features.length){
            featuresHtml+='<div class="modal-features"><h4>Key Features</h4><ul>';
            p.features.forEach(function(f){
                f=esc(typeof f==='object'?(f.text||f.feature||f.title||''):f);
                featuresHtml+='<li>'+f+'</li>';
            });
            featuresHtml+='</ul></div>';
        }
        var reviewsHtml='';
        if(reviews.length){
            reviewsHtml+='<div class="modal-features" style="border-top:1px solid var(--gray-100);padding-top:1rem;"><h4>Customer Reviews ('+reviews.length+')</h4>';
            reviews.slice(0,3).forEach(function(rev){
                reviewsHtml+='<div style="margin-bottom:.75rem;"><span style="color:#f59e0b;font-size:.85rem;">'+stars(rev.rating)+'</span>'
                    +'<p style="font-size:.88rem;color:var(--gray-600);line-height:1.6;margin:.2rem 0;">&quot;'+esc(rev.review_text||'')+'&quot;</p>'
                    +'<div style="font-size:.78rem;font-weight:600;color:var(--gray-500);">'+esc(rev.customer_name||'Customer')+'</div></div>';
            });
            reviewsHtml+='</div>';
        }
        var descHtml=p.description?'<div class="modal-desc" id="modalDesc">'+esc(p.description)+'</div>':'';
        var priceBlock='<div class="modal-price-row">'
            +'<span class="modal-price">'+money(p.effective_price!=null?p.effective_price:p.price)+'</span>';
        if(p.old_price&&p.old_price>p.price) priceBlock+='<span class="modal-old-price">'+money(p.old_price)+'</span>';
        if(p.discount_percentage&&p.discount_percentage>0) priceBlock+='<span class="modal-discount">-'+p.discount_percentage+'%</span>';
        priceBlock+='</div>';

        var imgHtml='';
        if(p.image) imgHtml='<img src="/storage/products/'+esc(p.image)+'" alt="'+esc(p.title)+'" class="modal-img">';

        var buyBtn=isAvailable
            ?'<a href="/checkout/'+p.id+'" class="modal-buy">Buy Now — '+money(p.effective_price!=null?p.effective_price:p.price)+'</a>'
            :'<button class="modal-buy" disabled style="opacity:.5;cursor:not-allowed;width:100%;padding:.85rem;background:var(--gray-300);color:var(--white);font-weight:700;font-size:1rem;border:none;border-radius:var(--radius);">Sold Out</button>';

        pcb.innerHTML=imgHtml
            +'<div class="modal-body">'
            +'<h3 class="modal-title">'+esc(p.title)+'</h3>'
            +(p.subtitle?'<p class="modal-subtitle">'+esc(p.subtitle)+'</p>':'')
            +priceBlock
            +descHtml
            +featuresHtml
            +reviewsHtml
            +buyBtn
            +'</div>';
    }

    window.openProductModal=function(id){
        pm.classList.add('open');
        document.body.style.overflow='hidden';
        pcb.innerHTML='<div class="modal-loading">Loading</div>';
        fetch('/product-details/'+id,{headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json'}})
            .then(function(r){if(!r.ok)throw new Error('bad');return r.json();})
            .then(function(data){render(data);})
            .catch(function(){pcb.innerHTML='<div style="padding:2.5rem;text-align:center;color:var(--gray-500);">Failed to load product details.</div>';});
    };
    function closePM(){
        pm.classList.remove('open');
        document.body.style.overflow='';
    }
    pcm.addEventListener('click',closePM);
    pm.addEventListener('click',function(e){if(e.target===pm)closePM();});
    document.addEventListener('keydown',function(e){if(e.key==='Escape'&&pm.classList.contains('open'))closePM();});
})();
</script>