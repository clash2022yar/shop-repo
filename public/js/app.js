// Digino - Global JS with AJAX, animations, no refresh patterns
function showToast(message, type='success'){
  const container = document.getElementById('toast-container');
  if(!container) return alert(message);
  const colors = {success:'bg-[#0aad64] text-white', error:'bg-[#ef4056] text-white', info:'bg-[#334155] text-white'};
  const el = document.createElement('div');
  el.className = `pointer-events-auto min-w-[280px] max-w-[400px] px-4 py-3 rounded-xl shadow-2xl text-sm font-medium flex items-center gap-3 translate-y-4 opacity-0 transition-all duration-300 ${colors[type]||colors.info}`;
  el.innerHTML = `<span class="w-2 h-2 bg-white rounded-full animate-pulse shrink-0"></span><span class="flex-1">${message}</span><button onclick="this.parentElement.remove()" class="opacity-60 hover:opacity-100">×</button>`;
  container.appendChild(el);
  requestAnimationFrame(()=>{ el.classList.remove('translate-y-4','opacity-0')});
  setTimeout(()=>{ el.classList.add('translate-y-2','opacity-0'); setTimeout(()=> el.remove(),300)}, 3500);
}

// Mega menu
document.addEventListener('DOMContentLoaded', ()=>{
  const btn=document.getElementById('megaMenuBtn');
  const menu=document.getElementById('megaMenu');
  if(btn && menu){
    let timeout;
    btn.addEventListener('mouseenter',()=>{ clearTimeout(timeout); menu.classList.remove('hidden'); });
    btn.addEventListener('mouseleave',()=>{ timeout=setTimeout(()=> menu.classList.add('hidden'),200)});
    menu.addEventListener('mouseenter',()=> clearTimeout(timeout));
    menu.addEventListener('mouseleave',()=> menu.classList.add('hidden'));
    btn.addEventListener('click',()=> menu.classList.toggle('hidden'));
  }

  // Header scroll shadow
  const header=document.querySelector('header');
  if(header){
    window.addEventListener('scroll',()=>{
      if(window.scrollY>10) header.classList.add('shadow-md');
      else header.classList.remove('shadow-md');
    });
  }

  // Update cart count from localStorage
  const saved=JSON.parse(localStorage.getItem('digino_cart')||'[]');
  const countEl=document.getElementById('cart-count');
  if(countEl && saved.length){
    countEl.textContent = saved.reduce((s,i)=>s+i.qty,0);
  }

  // Animate on scroll
  const observer=new IntersectionObserver((entries)=>{
    entries.forEach(e=>{
      if(e.isIntersecting){ e.target.classList.add('animate-[fadeIn_0.6s_ease-out]'); observer.unobserve(e.target); }
    });
  },{threshold:0.1});
  document.querySelectorAll('section, .hover\\:shadow-lg').forEach(el=> observer.observe(el));

  // Newsletter
  const nl=document.getElementById('newsletterForm');
  if(nl){
    nl.addEventListener('submit',e=>{
      e.preventDefault();
      const email=e.target.querySelector('input[type="email"]').value;
      if(!email.includes('@')) return showToast('ایمیل نامعتبر است','error');
      fetch('/api/newsletter',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({email})}).catch(()=>{});
      showToast('عضویت شما با موفقیت ثبت شد 🎉','success');
      e.target.reset();
    });
  }
});

// Cart helpers
function addToCart(name, price){
  let cart=JSON.parse(localStorage.getItem('digino_cart')||'[]');
  const existing=cart.find(i=>i.name===name);
  if(existing) existing.qty++;
  else cart.push({name,price,qty:1});
  localStorage.setItem('digino_cart',JSON.stringify(cart));
  const count=cart.reduce((s,i)=>s+i.qty,0);
  const badge=document.getElementById('cart-count');
  if(badge){ badge.textContent=count; badge.classList.add('animate-bounce'); setTimeout(()=> badge.classList.remove('animate-bounce'),600); }
  // AJAX to Laravel
  fetch('/cart/add',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]')?.content||'','X-Requested-With':'XMLHttpRequest'},body:JSON.stringify({product_id:1,quantity:1})}).catch(()=>{});
  showToast(`«${name}» به سبد اضافه شد`,'success');
}

function toggleWishlist(btn){
  btn.classList.toggle('text-[#ef4056]');
  btn.classList.toggle('fill-[#ef4056]');
  const isActive=btn.classList.contains('text-[#ef4056]');
  // AJAX wishlist toggle
  fetch('/wishlist/toggle',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]')?.content||''},body:JSON.stringify({product_id:1})}).catch(()=>{});
  showToast(isActive? 'به علاقه‌مندی‌ها اضافه شد':'از علاقه‌مندی‌ها حذف شد', isActive?'success':'info');
  // animation
  btn.animate([{transform:'scale(1)'},{transform:'scale(1.3)'},{transform:'scale(1)'}],{duration:300});
}

function closeModal(){
  const m=document.getElementById('quickViewModal');
  if(m){ m.classList.add('opacity-0'); setTimeout(()=>{m.classList.add('hidden'); m.classList.remove('flex')},300)}
}
function openQuickView(html){
  const m=document.getElementById('quickViewModal');
  const body=document.getElementById('quickViewBody');
  if(m && body){ body.innerHTML=html; m.classList.remove('hidden'); m.classList.add('flex'); requestAnimationFrame(()=> m.classList.remove('opacity-0')); }
}

// Search autocomplete (AJAX)
let searchTimeout;
document.querySelectorAll('input[name="q"]').forEach(inp=>{
  inp.addEventListener('input',e=>{
    clearTimeout(searchTimeout);
    const q=e.target.value.trim();
    if(q.length<2) return;
    searchTimeout=setTimeout(()=>{
      fetch(`/search?q=${encodeURIComponent(q)}`,{headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json'}})
        .then(r=>r.json()).catch(()=>{})
        .then(data=>{ if(data) console.log('search results',data); });
    },350);
  });
});

// Live price filter (AJAX)
const priceRange=document.getElementById('priceRange');
if(priceRange){
  priceRange.addEventListener('input',e=>{
    const val=parseInt(e.target.value);
    document.getElementById('maxPrice') && (document.getElementById('maxPrice').value = val.toLocaleString('fa-IR'));
    // debounce AJAX
    clearTimeout(window.priceTimeout);
    window.priceTimeout=setTimeout(()=>{
      fetch(`/shop?max_price=${val}`,{headers:{'X-Requested-With':'XMLHttpRequest'}}).then(()=> showToast('فیلتر قیمت اعمال شد','info'));
    },500);
  });
}

// Add smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(a=>{
  a.addEventListener('click',e=>{
    const id=a.getAttribute('href');
    if(id.length>1){
      const el=document.querySelector(id);
      if(el){ e.preventDefault(); el.scrollIntoView({behavior:'smooth'})}
    }
  });
});

// CSRF token for AJAX (Laravel)
const csrfMeta=document.querySelector('meta[name="csrf-token"]');
if(!csrfMeta){
  const m=document.createElement('meta');
  m.name='csrf-token';
  m.content='digino-csrf-token-demo';
  document.head.appendChild(m);
}
