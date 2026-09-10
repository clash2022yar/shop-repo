import http from 'http';
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
const PORT = process.env.PORT || 3000;

function readFileSafe(p){
  try { return fs.readFileSync(p,'utf8'); } catch(e){ return null; }
}

function bladeToHtml(bladePath){
  let content = readFileSafe(bladePath);
  if(!content) return `<h1>404 - Not Found</h1><p>File not found: ${bladePath}</p>`;
  // Remove Blade directives that are not needed for static preview
  // Handle @extends -> include layouts
  // For simplicity, if extends layouts.app, wrap with header/footer
  // Extract @section('content') ... @endsection
  let body = content;
  // Try to extract content section
  const sectionMatch = content.match(/@section\('content'\)([\s\S]*?)@endsection/);
  if(sectionMatch){
    body = sectionMatch[1];
  } else if(content.includes("@section('content')")){
    // fallback: remove up to endsection
    body = content.replace(/@extends[\s\S]*?@section\('content'\)/, '').replace(/@endsection[\s\S]*/, '');
  }
  // Remove other Blade directives
  body = body
    .replace(/@extends\([^)]+\)/g,'')
    .replace(/@section\([^)]+\)/g,'')
    .replace(/@endsection/g,'')
    .replace(/@push\([^)]+\)/g,'')
    .replace(/@endpush/g,'')
    .replace(/@stack\([^)]+\)/g,'')
    .replace(/@yield\([^)]+\)/g,'')
    .replace(/@include\([^)]+\)/g,'')
    .replace(/@php[\s\S]*?@endphp/g,'')
    .replace(/@if[\s\S]*?@endif/g,'')
    .replace(/@foreach[\s\S]*?@endforeach/g,'')
    .replace(/@for[\s\S]*?@endfor/g,'')
    .replace(/\{\{[^}]+\}\}/g, (m)=>{
      // replace Blade echoes with demo values
      if(m.includes('csrf_token')) return 'demo-csrf';
      if(m.includes('request(')) return '';
      if(m.includes('route(')) return '#';
      if(m.includes('auth()')) return 'علی محمدی';
      return '';
    })
    .replace(/\{!![^}]+\!\}/g,'')
    .replace(/@csrf/g,'<input type="hidden" name="_token" value="demo">')
    .replace(/@method\([^)]+\)/g,'')
    ;

  // Wrap with layout
  const header = readFileSafe(path.join(__dirname,'resources/views/includes/header.blade.php')) || '';
  const footer = readFileSafe(path.join(__dirname,'resources/views/includes/footer.blade.php')) || '';
  const headerMinimal = readFileSafe(path.join(__dirname,'resources/views/includes/header-minimal.blade.php')) || '';

  // Determine layout type from original blade
  const isMinimal = content.includes("layouts.minimal");
  const isAdmin = content.includes("layouts.admin");
  let layoutHeader = '';
  let layoutFooter = '';
  if(isAdmin){
    const sidebar = readFileSafe(path.join(__dirname,'resources/views/includes/sidebar-admin.blade.php')) || '';
    // Admin layout is custom - return body wrapped in admin chrome
    const adminLayout = readFileSafe(path.join(__dirname,'resources/views/layouts/admin.blade.php')) || '';
    // For preview, just return body with admin sidebar inline
    return `<!DOCTYPE html><html lang="fa" dir="rtl"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>دیجینو ادمین</title><link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700&display=swap" rel="stylesheet"><script src="https://cdn.tailwindcss.com"></script><style>*{font-family:'Vazirmatn',sans-serif} body{background:#f8fafc}</style></head><body><div class="flex min-h-screen"><div class="w-[260px] bg-[#1e293b] text-white hidden lg:block">${sidebar.replace(/@[^ \n]+/g,'')}</div><div class="flex-1"><header class="h-[64px] bg-white border-b flex items-center px-6"><h1 class="font-bold">پنل ادمین — دیجینو</h1><a href="/" class="mr-auto text-sm text-[#ef4056]">بازگشت به سایت</a></header><main class="p-6">${body}</main></div></div><script src="/js/app.js"></script></body></html>`;
  } else if(isMinimal){
    layoutHeader = headerMinimal;
  } else {
    layoutHeader = header;
    layoutFooter = footer;
  }

  // Clean header/footer from Blade syntax as well
  function cleanBlade(str){
    return str
      .replace(/@[^ \n]+(\([^)]+\))?/g,'')
      .replace(/\{\{[^}]+\}\}/g,'')
      .replace(/\{!![^}]+\!\}/g,'')
      .replace(/@csrf/g,'');
  }
  layoutHeader = cleanBlade(layoutHeader);
  layoutFooter = cleanBlade(layoutFooter);

  // Build final HTML
  const html = `<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>دیجینو — فروشگاه اینترنتی</title>
<link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script>tailwind.config={theme:{extend:{fontFamily:{vazir:['Vazirmatn','sans-serif']},colors:{primary:'#ef4056'}}}}</script>
<style>*{font-family:'Vazirmatn',sans-serif} body{background:#f5f5f7} ::-webkit-scrollbar{width:8px} ::-webkit-scrollbar-thumb{background:#cbd5e1;border-radius:999px}</style>
</head>
<body class="font-vazir">
${layoutHeader}
<main>${body}</main>
${layoutFooter}
<div id="toast-container" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[999] flex flex-col gap-2 pointer-events-none"></div>
<script src="/js/app.js"></script>
</body>
</html>`;
  return html;
}

const routes = {
  '/': 'resources/views/front/home.blade.php',
  '/home': 'resources/views/front/home.blade.php',
  '/shop': 'resources/views/front/shop.blade.php',
  '/products': 'resources/views/front/shop.blade.php',
  '/category': 'resources/views/front/category.blade.php',
  '/search': 'resources/views/front/search.blade.php',
  '/product': 'resources/views/front/product.blade.php',
  '/cart': 'resources/views/front/cart.blade.php',
  '/checkout': 'resources/views/front/checkout.blade.php',
  '/checkout/success': 'resources/views/front/checkout-success.blade.php',
  '/login': 'resources/views/auth/login.blade.php',
  '/register': 'resources/views/auth/register.blade.php',
  '/dashboard': 'resources/views/user/dashboard.blade.php',
  '/dashboard/orders': 'resources/views/user/orders.blade.php',
  '/dashboard/orders/1': 'resources/views/user/order-details.blade.php',
  '/dashboard/wishlist': 'resources/views/user/wishlist.blade.php',
  '/dashboard/addresses': 'resources/views/user/addresses.blade.php',
  '/dashboard/profile': 'resources/views/user/profile.blade.php',
  '/about': 'resources/views/front/about.blade.php',
  '/contact': 'resources/views/front/contact.blade.php',
  '/admin': 'resources/views/admin/dashboard.blade.php',
  '/admin/dashboard': 'resources/views/admin/dashboard.blade.php',
  '/admin/products': 'resources/views/admin/products/index.blade.php',
  '/admin/products/create': 'resources/views/admin/products/create.blade.php',
  '/admin/products/1/edit': 'resources/views/admin/products/edit.blade.php',
  '/admin/categories': 'resources/views/admin/categories/index.blade.php',
  '/admin/categories/create': 'resources/views/admin/categories/create.blade.php',
  '/admin/orders': 'resources/views/admin/orders/index.blade.php',
  '/admin/orders/1': 'resources/views/admin/orders/show.blade.php',
  '/admin/customers': 'resources/views/admin/customers/index.blade.php',
  '/admin/customers/1': 'resources/views/admin/customers/show.blade.php',
  '/admin/coupons': 'resources/views/admin/coupons/index.blade.php',
  '/admin/reviews': 'resources/views/admin/reviews/index.blade.php',
  '/admin/inventory': 'resources/views/admin/inventory/index.blade.php',
  '/admin/banners': 'resources/views/admin/banners/index.blade.php',
  '/admin/settings': 'resources/views/admin/settings/index.blade.php',
};

function serveStatic(req,res){
  let filePath = path.join(__dirname, 'public', req.url === '/' ? 'index.html' : req.url);
  // prevent directory traversal
  if(!filePath.startsWith(path.join(__dirname,'public'))){
    res.writeHead(403); return res.end('Forbidden');
  }
  // if path ends with /, try index.html
  if(fs.existsSync(filePath) && fs.statSync(filePath).isDirectory()){
    filePath = path.join(filePath,'index.html');
  }
  if(fs.existsSync(filePath) && fs.statSync(filePath).isFile()){
    const ext = path.extname(filePath).toLowerCase();
    const mime = {'.html':'text/html','.css':'text/css','.js':'application/javascript','.json':'application/json','.jpg':'image/jpeg','.jpeg':'image/jpeg','.png':'image/png','.svg':'image/svg+xml','.ico':'image/x-icon'}[ext] || 'application/octet-stream';
    res.writeHead(200, {'Content-Type': mime, 'Cache-Control':'public, max-age=3600', 'Access-Control-Allow-Origin':'*'});
    return fs.createReadStream(filePath).pipe(res);
  }
  return null;
}

const server = http.createServer((req,res)=>{
  // CORS
  res.setHeader('Access-Control-Allow-Origin','*');
  res.setHeader('Access-Control-Allow-Methods','GET,POST,PUT,PATCH,DELETE,OPTIONS');
  res.setHeader('Access-Control-Allow-Headers','Content-Type, X-CSRF-TOKEN, X-Requested-With, Accept');
  if(req.method==='OPTIONS'){ res.writeHead(200); return res.end(); }

  const url = new URL(req.url, `http://${req.headers.host}`);
  const pathname = url.pathname;

  // Handle API / AJAX POST dummy
  if(req.method==='POST' || req.method==='PATCH' || req.method==='DELETE'){
    let body='';
    req.on('data',chunk=> body+=chunk);
    req.on('end',()=>{
      res.writeHead(200,{'Content-Type':'application/json'});
      res.end(JSON.stringify({message:'عملیات موفق (دمو) - AJAX', status:'success', data: {}}));
    });
    return;
  }

  // Try static first
  if(pathname.startsWith('/images/') || pathname.startsWith('/js/') || pathname.startsWith('/css/') || pathname.startsWith('/favicon')){
    const staticRes = serveStatic(req,res);
    if(staticRes !== null) return;
  }
  if(pathname === '/js/app.js' || pathname === '/css/app.css'){
    const staticRes = serveStatic(req,res);
    if(staticRes !== null) return;
  }

  // Route mapping
  let bladeFile = routes[pathname];
  // Dynamic product slug
  if(!bladeFile && pathname.startsWith('/product/')){
    bladeFile = 'resources/views/front/product.blade.php';
  }
  if(!bladeFile && pathname.startsWith('/category/')){
    bladeFile = 'resources/views/front/category.blade.php';
  }
  if(!bladeFile && pathname.startsWith('/admin/products/') && pathname.endsWith('/edit')){
    bladeFile = 'resources/views/admin/products/edit.blade.php';
  }
  if(!bladeFile && pathname.startsWith('/dashboard/orders/')){
    bladeFile = 'resources/views/user/order-details.blade.php';
  }
  if(!bladeFile && pathname.startsWith('/checkout/success')){
    bladeFile = 'resources/views/front/checkout-success.blade.php';
  }
  // Fallback to home for unknown
  if(!bladeFile){
    // Try to serve as static file if exists
    const staticTry = serveStatic(req,res);
    if(staticTry !== null) return;
    // otherwise 404 but show home with message
    bladeFile = 'resources/views/front/home.blade.php';
  }

  const html = bladeToHtml(path.join(__dirname, bladeFile));
  res.writeHead(200, {'Content-Type':'text/html; charset=utf-8', 'Cache-Control':'no-cache'});
  res.end(html);
});

server.listen(PORT,'0.0.0.0',()=>{
  console.log(`Digino server running on http://0.0.0.0:${PORT}`);
  console.log(`Preview: https://${PORT}-${process.env.E2B_SANDBOX_ID || 'preview'}.e2b.app`);
});
