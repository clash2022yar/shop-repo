/**
 * Static preview builder.
 *
 * Renders the real Blade views with the mini-Blade engine and fixture data, and
 * writes one HTML file per screen into preview/dist so the design can be
 * reviewed in a browser without a PHP runtime.
 *
 *   node preview/build.js
 */
'use strict';

const fs = require('fs');
const path = require('path');
const { Blade } = require('./blade');
const S = require('./support');
const { buildWorld, OrderStatus, PaymentStatus, ReviewStatus, UserRole } = require('./fixtures');

const ROOT = path.resolve(__dirname, '..');
const DIST = path.join(ROOT, 'preview/dist');
const { Collection, Paginator, DateTime, collect } = S;

const world = buildWorld();

/* ════════════════════════════════ routing ════════════════════════════════ */

/** Route name → static file produced by this builder. */
const ROUTES = {
    home: 'index.html',
    'shop.index': 'shop.html',
    'shop.special': 'special.html',
    'products.show': 'product.html',
    'categories.index': 'categories.html',
    'categories.show': 'category.html',
    category: 'category.html',
    'brands.index': 'brands.html',
    'brands.show': 'brand.html',
    search: 'search.html',
    compare: 'compare.html',
    'cart.index': 'cart.html',
    'checkout.index': 'checkout.html',
    'checkout.payment': 'checkout-payment.html',
    'checkout.result': 'checkout-result.html',
    'blog.index': 'blog.html',
    'blog.show': 'blog-post.html',
    'pages.show': 'page.html',
    about: 'about.html',
    contact: 'contact.html',
    faq: 'faq.html',
    terms: 'terms.html',
    privacy: 'privacy.html',

    login: 'login.html',
    register: 'register.html',
    'password.request': 'forgot-password.html',
    'password.reset': 'reset-password.html',

    'account.dashboard': 'account.html',
    'account.orders.index': 'account-orders.html',
    'account.orders.show': 'account-order.html',
    'account.orders.invoice': 'account-invoice.html',
    'account.addresses': 'account-addresses.html',
    'account.wishlist': 'account-wishlist.html',
    'account.reviews': 'account-reviews.html',
    'account.recently-viewed': 'account-recently-viewed.html',
    'account.notifications': 'account-notifications.html',
    'account.payments': 'account-payments.html',
    'account.profile': 'account-profile.html',
    'account.security': 'account-security.html',
    'account.tickets.index': 'account-tickets.html',
    'account.tickets.create': 'account-ticket-create.html',
    'account.tickets.show': 'account-ticket.html',

    'admin.dashboard': 'admin.html',
    'admin.products.index': 'admin-products.html',
    'admin.products.create': 'admin-product-create.html',
    'admin.products.edit': 'admin-product-edit.html',
    'admin.categories.index': 'admin-categories.html',
    'admin.brands.index': 'admin-brands.html',
    'admin.orders.index': 'admin-orders.html',
    'admin.orders.show': 'admin-order.html',
    'admin.orders.invoice': 'admin-order-invoice.html',
    'admin.customers.index': 'admin-customers.html',
    'admin.customers.show': 'admin-customer.html',
    'admin.coupons.index': 'admin-coupons.html',
    'admin.reviews.index': 'admin-reviews.html',
    'admin.questions.index': 'admin-questions.html',
    'admin.inventory.index': 'admin-inventory.html',
    'admin.inventory.movements': 'admin-movements.html',
    'admin.banners.index': 'admin-banners.html',
    'admin.posts.index': 'admin-posts.html',
    'admin.posts.create': 'admin-post-create.html',
    'admin.posts.edit': 'admin-post-edit.html',
    'admin.pages.index': 'admin-pages.html',
    'admin.tickets.index': 'admin-tickets.html',
    'admin.tickets.show': 'admin-ticket.html',
    'admin.shipping.index': 'admin-shipping.html',
    'admin.staff.index': 'admin-staff.html',
    'admin.settings.index': 'admin-settings.html',
    'admin.reports.index': 'admin-reports.html',
};

let currentRouteName = 'home';

function route(name, params = null) {
    const file = ROUTES[name];
    if (!file) return '#';
    return `/${file}`;
}

function routeIs(...patterns) {
    return patterns.some((pattern) => {
        const re = new RegExp(`^${String(pattern).replace(/\./g, '\\.').replace(/\*/g, '.*')}$`);
        return re.test(currentRouteName);
    });
}

function asset(file) {
    if (!file) return '/images/placeholder-product.svg';
    if (/^https?:/.test(file)) return file;
    return `/${String(file).replace(/^\/+/, '')}`;
}

/* ════════════════════════════════ helpers ════════════════════════════════ */

const CONFIG = {
    'app.name': 'دیجی‌نو',
    'app.url': '/',
    'digino.brand.name': 'دیجی‌نو',
    'digino.brand.name_en': 'DigiNo',
    'digino.brand.creator': 'یارمحمدی',
    'digino.brand.creator_url': 'https://github.com/clash2022yar',
    'digino.version': '1.0.0',
    'digino.currency.code': 'IRT',
    'digino.currency.label': 'تومان',
    'digino.catalog.per_page': 24,
    'digino.catalog.related': 12,
    'digino.catalog.recently_viewed': 12,
    'digino.catalog.max_compare': 4,
    'digino.catalog.low_stock_threshold': 5,
    'digino.cart.max_qty_per_item': 5,
    'digino.checkout.free_shipping_from': 5_000_000,
    'digino.checkout.default_shipping_cost': 49_000,
    'digino.search.suggest_limit': 8,
    'digino.search.min_chars': 2,
    'digino.admin.per_page': 20,
    'services.zarinpal.merchant_id': null,
};

function config(key, fallback = null) {
    return CONFIG[key] !== undefined ? CONFIG[key] : fallback;
}

function digino(key, fallback = null) {
    const settings = world.settings;
    return settings[key] !== undefined && settings[key] !== null ? settings[key] : fallback;
}

/** The preview always renders as the demo customer, except on admin screens. */
let currentUser = world.demo;

function makeRequest(query = {}) {
    const bag = Object.assign({}, query);

    const api = {
        input: (key, fallback = null) => (bag[key] !== undefined ? bag[key] : fallback),
        get: (key, fallback = null) => api.input(key, fallback),
        query: (key, fallback = null) => api.input(key, fallback),
        has: (key) => bag[key] !== undefined,
        filled: (key) => bag[key] !== undefined && bag[key] !== '' && bag[key] !== null,
        boolean: (key) => !!bag[key],
        string: (key) => ({ toString: () => String(bag[key] ?? ''), value: () => String(bag[key] ?? ''), isEmpty: () => !bag[key] }),
        integer: (key, fallback = 0) => Number(bag[key] ?? fallback),
        all: () => bag,
        except: (keys) => {
            const copy = Object.assign({}, bag);
            (Array.isArray(keys) ? keys : [keys]).forEach((k) => delete copy[k]);
            return copy;
        },
        only: (keys) => Object.fromEntries((Array.isArray(keys) ? keys : [keys]).map((k) => [k, bag[k]])),
        routeIs: (...p) => routeIs(...p),
        is: (...p) => routeIs(...p),
        fullUrl: () => `/${ROUTES[currentRouteName] || ''}`,
        url: () => `/${ROUTES[currentRouteName] || ''}`,
        path: () => `/${ROUTES[currentRouteName] || ''}`,
        ajax: () => false,
        user: () => currentUser,
        route: () => ({ getName: () => currentRouteName }),
        session: () => sessionBag,
        expectsJson: () => false,
    };

    return api;
}

const sessionBag = {
    get: (key, fallback = null) => fallback,
    has: () => false,
    flash: () => {},
    token: () => 'preview-csrf-token',
    put: () => {},
};

/** `request()` is callable *and* an object in Blade views. */
/** `url()` is both callable and an object exposing current()/full()/previous(). */
function urlHelper(target = null) {
    const current = () => `/${ROUTES[currentRouteName] || ''}`;
    if (target !== null) return `/${String(target).replace(/^\/+/, '')}`;
    return { current, full: current, previous: () => '/', to: (t) => `/${String(t).replace(/^\/+/, '')}` };
}

function requestHelper(query = {}) {
    const api = makeRequest(query);
    const fn = (key = null, fallback = null) => (key === null ? api : api.input(key, fallback));
    Object.entries(api).forEach(([k, v]) => { fn[k] = v; });
    return fn;
}

function auth() {
    return {
        check: () => currentUser !== null,
        guest: () => currentUser === null,
        user: () => currentUser,
        id: () => (currentUser ? currentUser.id : null),
    };
}

function optional(value) {
    if (value !== null && value !== undefined) return value;

    const noop = () => '';
    return new Proxy(noop, { get: () => noop, apply: () => '' });
}

const strHelpers = {
    limit: (text, len = 100, end = '…') => (String(text || '').length > len ? String(text).slice(0, len) + end : String(text || '')),
    slug: (text) => String(text || '').trim().replace(/\s+/g, '-'),
    upper: (t) => String(t || '').toUpperCase(),
    lower: (t) => String(t || '').toLowerCase(),
    ucfirst: (t) => String(t || '').charAt(0).toUpperCase() + String(t || '').slice(1),
    of: (t) => ({ limit: (n) => strHelpers.limit(t, n), toString: () => String(t) }),
};

function helpers() {
    return {
        /* --- Laravel-ish globals -------------------------------------- */
        route,
        url: urlHelper,
        asset,
        config,
        digino,
        auth,
        request: requestHelper(),
        session: (key = null, fallback = null) => (key ? fallback : sessionBag),
        csrf_token: () => 'preview-csrf-token',
        old: (key, fallback = '') => fallback,
        optional,
        collect,
        now: () => DateTime.now(),
        today: () => DateTime.now().startOfDay(),
        abort: () => '',
        __: (k) => k,
        trans: (k) => k,
        e: (v) => v,

        /* --- project helpers ------------------------------------------ */
        toman: (v, withLabel = false) => S.toman(v, withLabel),
        fa_number: S.faNumber,
        en_number: S.enNumber,
        jalali: (v, withTime = false) => S.jalali(v, withTime),
        jalali_human: S.jalaliHuman,
        svg_icon: (name, cls = 'h-5 w-5') => blade.renderComponent('icon', { name, class: cls }, '', {}, { sections: {}, stacks: {}, once: new Set() }),
        cache_remember_short: (key, fn) => fn(),

        /* --- PHP standard library ------------------------------------- */
        count: (v) => (v instanceof Collection ? v.count() : (Array.isArray(v) ? v.length : Object.keys(v || {}).length)),
        number_format: S.numberFormat,
        max: Math.max,
        min: Math.min,
        abs: Math.abs,
        round: (v, p = 0) => Number(Math.round(Number(v) + `e${p}`) + `e-${p}`) || Math.round(Number(v)),
        floor: Math.floor,
        ceil: Math.ceil,
        trim: (v, chars = ' ') => String(v ?? '').trim(),
        strtoupper: (v) => String(v ?? '').toUpperCase(),
        strtolower: (v) => String(v ?? '').toLowerCase(),
        strip_tags: (v) => String(v ?? '').replace(/<[^>]*>/g, ''),
        nl2br: (v) => String(v ?? '').replace(/\n/g, '<br>'),
        str_replace: (search, replace, subject) => String(subject ?? '').split(search).join(replace),
        str_contains: (haystack, needle) => String(haystack ?? '').includes(needle),
        str_pad: (v, len, pad = ' ') => String(v ?? '').padStart(len, pad),
        substr: (v, start, len) => String(v ?? '').substr(start, len),
        mb_substr: (v, start, len) => String(v ?? '').substr(start, len),
        mb_strlen: (v) => String(v ?? '').length,
        strlen: (v) => String(v ?? '').length,
        ucfirst: strHelpers.ucfirst,
        implode: (glue, arr) => (arr instanceof Collection ? arr.toArray() : (arr || [])).join(glue),
        explode: (glue, text) => String(text ?? '').split(glue),
        preg_split: (pattern, text) => String(text ?? '').split(/[\r\n،,]+/).filter(Boolean),
        in_array: (needle, haystack) => (haystack instanceof Collection ? haystack.toArray() : (haystack || [])).includes(needle),
        array_filter: (arr, fn = (v) => !!v) => (Array.isArray(arr) ? arr.filter(fn) : Object.fromEntries(Object.entries(arr || {}).filter(([, v]) => fn(v)))),
        array_keys: (arr) => (Array.isArray(arr) ? arr.map((_, i) => i) : Object.keys(arr || {})),
        array_values: (arr) => (Array.isArray(arr) ? arr : Object.values(arr || {})),
        array_merge: (...arrays) => Object.assign({}, ...arrays),
        array_sum: (arr) => (Array.isArray(arr) ? arr : Object.values(arr || {})).reduce((c, v) => c + Number(v || 0), 0),
        array_slice: (arr, start, len) => (Array.isArray(arr) ? arr.slice(start, len ? start + len : undefined) : Object.values(arr || {}).slice(start, len ? start + len : undefined)),
        is_array: (v) => Array.isArray(v) || v instanceof Collection,
        is_string: (v) => typeof v === 'string',
        is_null: (v) => v === null || v === undefined,
        is_numeric: (v) => !Number.isNaN(Number(v)),
        json_decode: (v) => { try { return JSON.parse(v); } catch { return null; } },
        json_encode: (v) => JSON.stringify(v),
        uniqid: () => `id${Math.random().toString(36).slice(2, 10)}`,
        filled: (v) => !S.empty(v),
        blank: (v) => S.empty(v),
        intdiv: (a, b) => Math.floor(a / b),
        rand: (a, b) => a + Math.floor(Math.random() * (b - a + 1)),

        /* --- JavaScript globals (the `with` scope shadows them) --------- */
        JSON, Math, String, Number, Boolean, Array, Object, Date, RegExp, console,
        parseInt, parseFloat, isNaN, encodeURIComponent, decodeURIComponent, Intl,
        empty: S.empty,
        isset: (v) => v !== null && v !== undefined,

        /* --- engine internals ----------------------------------------- */
        __items: (v) => {
            if (v instanceof Collection) return v.toArray();
            if (Array.isArray(v)) return v;
            if (v && typeof v === 'object') return Object.values(v);
            return [];
        },
        __keys: (v) => {
            if (v instanceof Collection) return v.keysList || v.toArray().map((_, i) => i);
            if (Array.isArray(v)) return v.map((_, i) => i);
            if (v && typeof v === 'object') return Object.keys(v);
            return [];
        },
        __empty: S.empty,
        __pairs: (v) => {
            if (v instanceof Collection) return v.toObject ? v.toObject() : Object.assign({}, v.toArray());
            if (Array.isArray(v)) return Object.assign({}, v);
            return v || {};
        },
        __classes: S.classes,
        __auth: () => currentUser !== null,
        __isAdmin: () => currentUser !== null && currentUser.isAdmin(),
        __isSuperAdmin: () => currentUser !== null && currentUser.isSuperAdmin(),
        __can: () => currentUser !== null && currentUser.isAdmin(),
        __error: () => null,
        __match: (value, map) => (map[value] !== undefined ? map[value] : map.default),

        /* --- facades & classes used from views ------------------------- */
        Gate: { allows: () => currentUser !== null && currentUser.isAdmin(), denies: () => false },
        View: { getSection: (name, fallback = '') => fallback, hasSection: () => false },
        Auth: { check: () => currentUser !== null, user: () => currentUser },
        Str: strHelpers,
        Arr: { get: (arr, key, fallback = null) => (arr && arr[key] !== undefined ? arr[key] : fallback) },
        Carbon: DateTime,
        Paginator, LengthAwarePaginator: Paginator,
        OrderStatus,
        PaymentStatus,
        ReviewStatus,
        UserRole,
        Iran: {
            provinces: () => Object.keys({ 'تهران': 1, 'البرز': 1, 'اصفهان': 1, 'فارس': 1, 'خراسان رضوی': 1, 'آذربایجان شرقی': 1, 'خوزستان': 1, 'گیلان': 1, 'مازندران': 1, 'کرمان': 1 }),
            cities: () => ['تهران', 'کرج', 'اصفهان', 'شیراز', 'مشهد'],
            map: () => ({ 'تهران': ['تهران', 'ری', 'شهریار'], 'البرز': ['کرج', 'فردیس'] }),
        },
        App: { Models: {}, Enums: { OrderStatus, PaymentStatus, ReviewStatus, UserRole } },
    };
}

/* ═══════════════════════════════ shared data ══════════════════════════════ */

const menuCategories = new Collection(world.categories.filter((c) => c.parent_id === null));
const footerPages = new Collection(world.pages.filter((p) => p.in_footer));

function cartItems() {
    return new Collection(collect(world.products).filter((p) => p.stock > 0).take(3).toArray().map((product, i) => ({
        id: 900 + i,
        product,
        product_id: product.id,
        variant: product.variants.first(),
        product_variant_id: product.variants.first()?.id || null,
        quantity: i + 1,
        unit_price: product.price,
        line_total: product.price * (i + 1),
        is_selected: true,
        max: Math.min(product.stock, 5),
    })));
}

function cartSummary(items) {
    const list = items || cartItems();
    const subtotal = list.toArray().reduce((c, i) => c + i.line_total, 0);
    const productDiscount = list.toArray().reduce((c, i) => c + (i.product.compare_at_price ? (i.product.compare_at_price - i.product.price) * i.quantity : 0), 0);
    const freeFrom = config('digino.checkout.free_shipping_from');
    const shipping = subtotal >= freeFrom ? 0 : config('digino.checkout.default_shipping_cost');
    const grandTotal = Math.max(0, subtotal + shipping);

    return {
        count: list.toArray().reduce((c, i) => c + i.quantity, 0),
        selected_count: list.toArray().reduce((c, i) => c + i.quantity, 0),
        lines: list.count(),
        subtotal,
        product_discount: productDiscount,
        coupon_discount: 0,
        coupon_code: null,
        shipping_cost: shipping,
        free_shipping: shipping === 0 && subtotal > 0,
        free_shipping_remaining: Math.max(0, freeFrom - subtotal),
        grand_total: grandTotal,
        formatted: {
            subtotal: S.toman(subtotal, false),
            product_discount: S.toman(productDiscount, false),
            coupon_discount: S.toman(0, false),
            shipping_cost: shipping === 0 ? 'رایگان' : S.toman(shipping, false),
            grand_total: S.toman(grandTotal, false),
        },
    };
}

const sharedCartItems = cartItems();
const sharedSummary = cartSummary(sharedCartItems);

function sidebarCounts() {
    return {
        orders: world.demo.orders.filter((o) => o.status.isOpen()).count(),
        wishlist: world.demo.wishlists.count(),
        reviews: world.demo.reviews.count(),
        addresses: world.demo.addresses.count(),
        notifications: world.demo.unreadNotifications.count(),
        tickets: world.demo.tickets.filter((t) => t.status !== 'closed').count(),
    };
}

const adminBadges = {
    new_orders: world.orders.filter((o) => o.status.value === 'pending').length,
    pending_reviews: world.reviews.filter((r) => r.status.value === 'pending').length,
    pending_questions: world.questions.filter((q) => q.status === 'pending').length,
    open_tickets: world.tickets.filter((t) => t.status !== 'closed').length,
    low_stock: world.products.filter((p) => p.stock > 0 && p.stock <= 5).length,
};

function paginate(items, perPage = 24, page = 1) {
    const list = items instanceof Collection ? items.toArray() : items;
    const paginator = new Paginator(list.slice((page - 1) * perPage, page * perPage), {
        total: list.length, perPage, currentPage: page, path: '#',
    });
    paginator.renderLinks = (p) => renderPagination(p);
    return paginator;
}

function renderPagination(paginator) {
    if (!paginator.hasPages()) return '';
    const last = paginator.lastPage();
    const current = paginator.currentPage();
    const pages = [];

    for (let i = 1; i <= Math.min(last, 7); i++) {
        pages.push(`<a href="#" data-page="${i}" class="page-link ${i === current ? 'is-active' : ''}">${S.faNumber(i)}</a>`);
    }

    return `<nav class="flex items-center justify-center gap-1.5 py-6" aria-label="صفحه‌بندی">
        <a href="#" data-page="${Math.max(1, current - 1)}" class="page-link" aria-label="صفحه قبل">‹</a>
        ${pages.join('')}
        <a href="#" data-page="${Math.min(last, current + 1)}" class="page-link" aria-label="صفحه بعد">›</a>
    </nav>`;
}

function facets(category = null) {
    const source = category
        ? world.products.filter((p) => p.category.id === category.id || p.category.parent_id === category.id)
        : world.products;

    const categories = (category && category.children.isNotEmpty() ? category.children.toArray() : world.categories.filter((c) => !c.parent_id))
        .map((c) => ({ name: c.name, slug: c.slug, count: world.products.filter((p) => p.category.id === c.id || p.category.parent_id === c.id).length }))
        .filter((c) => c.count > 0);

    const brands = world.brands
        .map((b) => ({ name: b.name, slug: b.slug, count: source.filter((p) => p.brand && p.brand.id === b.id).length }))
        .filter((b) => b.count > 0)
        .sort((a, b) => b.count - a.count);

    const colors = [];
    const seen = new Set();
    world.products.forEach((p) => p.variants.toArray().forEach((v) => {
        if (v.color_hex && !seen.has(v.color_hex) && colors.length < 14) {
            seen.add(v.color_hex);
            colors.push({ hex: v.color_hex, name: v.color_name });
        }
    }));

    return {
        categories,
        brands,
        colors,
        ratings: [4, 3, 2, 1].map((r) => ({ value: r, count: source.filter((p) => p.rating >= r).length })),
        price_min: Math.min(...source.map((p) => p.price)),
        price_max: Math.max(...source.map((p) => p.price)),
        sellers: [
            { key: 'digino', label: 'دیجی‌نو (ارسال توسط دیجی‌نو)', count: source.filter((p) => p.is_digino_seller).length },
            { key: 'other', label: 'فروشندگان دیگر', count: source.filter((p) => !p.is_digino_seller).length },
        ],
        total: source.length,
    };
}

/* ═══════════════════════════════ page catalogue ═══════════════════════════ */

const featured = collect(world.products).filter((p) => p.is_featured).take(12);
const specials = collect(world.products).filter((p) => p.is_special).take(12);
const bestSellers = collect(world.products).sortByDesc('sold_count').take(12);
const newArrivals = collect(world.products).sortByDesc('id').take(12);
const demoOrder = world.demo.orders.first() || world.orders[0];
const paidOrder = collect(world.orders).firstWhere('payment_status', PaymentStatus.Paid) || world.orders[0];
const sampleProduct = world.products.find((p) => p.stock > 0 && p.variants.isNotEmpty()) || world.products[0];
const sampleCategory = world.categoryBySlug['digital'];
const sampleBrand = world.brands[0];
const samplePost = world.posts[0];
const samplePage = world.pages[0];
const sampleTicket = world.demo.tickets.first();
const adminTicket = world.tickets[0];
const sampleCustomer = world.customers[3];

function breadcrumbs(...pairs) {
    return pairs.map(([label, url]) => ({ label, url }));
}

function productHistogram(product) {
    return [5, 4, 3, 2, 1].map((star) => ({
        star,
        count: product.reviews.filter((r) => r.rating === star).count(),
        percent: product.reviews.count() ? Math.round((product.reviews.filter((r) => r.rating === star).count() / product.reviews.count()) * 100) : 0,
    }));
}

const PAGES = [
    /* ─────────────────────────── storefront ─────────────────────────── */
    {
        file: 'index.html', view: 'pages.home', route: 'home',
        data: () => ({
            heroBanners: new Collection(world.banners.filter((b) => b.position === 'hero')),
            promoBanners: new Collection(world.banners.filter((b) => b.position.startsWith('promo'))),
            stripBanner: world.banners.find((b) => b.position === 'strip'),
            popularCategories: new Collection(world.categories.filter((c) => !c.parent_id)),
            specialOffers: specials,
            bestSellers,
            newArrivals,
            featured,
            posts: new Collection(world.posts.slice(0, 4)),
            featuredBrands: new Collection(world.brands.filter((b) => b.is_featured)),
            mostDiscounted: collect(world.products).sortByDesc('discount_percent').take(12),
            recentlyViewed: collect(world.products).take(8),
        }),
    },
    {
        file: 'shop.html', view: 'pages.shop', route: 'shop.index',
        data: () => ({
            title: 'همه محصولات',
            products: paginate(collect(world.products), 24, 1),
            facets: facets(),
            category: null,
            breadcrumbs: breadcrumbs(['همه محصولات', route('shop.index')]),
        }),
    },
    {
        file: 'special.html', view: 'pages.shop', route: 'shop.special',
        data: () => ({
            title: 'فروش ویژه دیجی‌نو',
            subtitle: 'پیشنهادهای شگفت‌انگیز با تخفیف‌های واقعی و زمان‌دار',
            products: paginate(collect(world.products).filter((p) => p.discount_percent > 0), 24, 1),
            facets: facets(),
            category: null,
            breadcrumbs: breadcrumbs(['فروش ویژه', route('shop.special')]),
        }),
    },
    {
        file: 'brand.html', view: 'pages.shop', route: 'brands.show',
        data: () => ({
            title: `محصولات ${sampleBrand.name}`,
            subtitle: sampleBrand.description,
            brand: sampleBrand,
            products: paginate(collect(world.products).filter((p) => p.brand && p.brand.id === sampleBrand.id), 24, 1),
            facets: facets(),
            category: null,
            breadcrumbs: breadcrumbs(['برندها', route('brands.index')], [sampleBrand.name, route('brands.show')]),
        }),
    },
    {
        file: 'category.html', view: 'pages.category', route: 'categories.show',
        data: () => ({
            category: sampleCategory,
            title: sampleCategory.name,
            subtitle: sampleCategory.description,
            products: paginate(collect(world.products).filter((p) => p.category.parent_id === sampleCategory.id), 24, 1),
            facets: facets(sampleCategory),
            breadcrumbs: breadcrumbs([sampleCategory.name, route('categories.show')]),
        }),
    },
    {
        file: 'categories.html', view: 'pages.categories', route: 'categories.index',
        data: () => ({
            categories: new Collection(world.categories.filter((c) => !c.parent_id)),
            breadcrumbs: breadcrumbs(['دسته‌بندی‌ها', route('categories.index')]),
        }),
    },
    {
        file: 'brands.html', view: 'pages.brands', route: 'brands.index',
        data: () => ({
            brands: new Collection(world.brands),
            breadcrumbs: breadcrumbs(['برندها', route('brands.index')]),
        }),
    },
    {
        file: 'search.html', view: 'pages.search', route: 'search',
        data: () => ({
            term: 'گوشی سامسونگ',
            products: paginate(collect(world.products).filter((p) => p.name.includes('سامسونگ')), 24, 1),
            facets: facets(),
            category: null,
            trending: new Collection(world.searchTerms.map((t) => t.term)),
            title: 'نتایج جستجو برای «گوشی سامسونگ»',
            breadcrumbs: breadcrumbs(['جستجو', route('search')]),
        }),
    },
    {
        file: 'product.html', view: 'pages.product', route: 'products.show',
        data: () => ({
            product: sampleProduct,
            related: collect(world.products).filter((p) => p.category.id === sampleProduct.category.id && p.id !== sampleProduct.id).take(12),
            sameBrand: collect(world.products).filter((p) => p.brand === sampleProduct.brand && p.id !== sampleProduct.id).take(8),
            histogram: productHistogram(sampleProduct),
            breadcrumbs: breadcrumbs([sampleProduct.category.name, route('categories.show')], [sampleProduct.name, route('products.show')]),
        }),
    },
    {
        file: 'compare.html', view: 'pages.compare', route: 'compare',
        data: () => {
            const products = collect(world.products).take(3);
            const rows = [];
            products.first().attributes.toArray().forEach((attr) => {
                rows.push({
                    name: attr.name,
                    values: products.toArray().map((p) => (p.attributes.firstWhere('name', attr.name) || {}).value || '—'),
                });
            });
            return { products, rows };
        },
    },
    {
        file: 'cart.html', view: 'pages.cart', route: 'cart.index',
        data: () => ({
            items: sharedCartItems,
            summary: sharedSummary,
            suggestions: collect(world.products).take(8),
            removed: new Collection([]),
            breadcrumbs: breadcrumbs(['سبد خرید', route('cart.index')]),
        }),
    },
    {
        file: 'checkout.html', view: 'pages.checkout', route: 'checkout.index',
        data: () => ({
            items: sharedCartItems,
            summary: sharedSummary,
            addresses: world.demo.addresses,
            methods: new Collection(world.shippingMethods),
            breadcrumbs: breadcrumbs(['سبد خرید', route('cart.index')], ['تکمیل سفارش', route('checkout.index')]),
        }),
    },
    { file: 'checkout-payment.html', view: 'pages.checkout-payment', route: 'checkout.payment', data: () => ({ order: demoOrder }) },
    { file: 'checkout-result.html', view: 'pages.checkout-result', route: 'checkout.result', data: () => ({ order: paidOrder }) },
    {
        file: 'blog.html', view: 'pages.blog-index', route: 'blog.index',
        data: () => ({
            posts: paginate(new Collection(world.posts), 9, 1),
            breadcrumbs: breadcrumbs(['مجله دیجی‌نو', route('blog.index')]),
        }),
    },
    {
        file: 'blog-post.html', view: 'pages.blog-show', route: 'blog.show',
        data: () => ({
            post: samplePost,
            related: new Collection(world.posts.slice(1, 4)),
            breadcrumbs: breadcrumbs(['مجله دیجی‌نو', route('blog.index')], [samplePost.title, route('blog.show')]),
        }),
    },
    {
        file: 'page.html', view: 'pages.static', route: 'pages.show',
        data: () => ({ page: samplePage, breadcrumbs: breadcrumbs([samplePage.title, route('pages.show')]) }),
    },
    {
        file: 'about.html', view: 'pages.about', route: 'about',
        data: () => ({
            stats: { products: world.products.length, customers: world.customers.length, orders: world.orders.length, brands: world.brands.length },
            breadcrumbs: breadcrumbs(['درباره دیجی‌نو', route('about')]),
        }),
    },
    { file: 'contact.html', view: 'pages.contact', route: 'contact', data: () => ({ breadcrumbs: breadcrumbs(['تماس با ما', route('contact')]) }) },
    { file: 'faq.html', view: 'pages.faq', route: 'faq', data: () => ({ breadcrumbs: breadcrumbs(['پرسش‌های متداول', route('faq')]) }) },
    { file: 'terms.html', view: 'pages.terms', route: 'terms', data: () => ({ breadcrumbs: breadcrumbs(['شرایط استفاده', route('terms')]) }) },
    { file: 'privacy.html', view: 'pages.privacy', route: 'privacy', data: () => ({ breadcrumbs: breadcrumbs(['حریم خصوصی', route('privacy')]) }) },
    { file: '404.html', view: 'errors.404', route: 'home', data: () => ({}) },

    /* ───────────────────────────── auth ─────────────────────────────── */
    { file: 'login.html', view: 'auth.login', route: 'login', guest: true, data: () => ({}) },
    { file: 'register.html', view: 'auth.register', route: 'register', guest: true, data: () => ({}) },
    { file: 'forgot-password.html', view: 'auth.forgot-password', route: 'password.request', guest: true, data: () => ({}) },
    { file: 'reset-password.html', view: 'auth.reset-password', route: 'password.reset', guest: true, data: () => ({ token: 'preview-token', email: 'user@digino.test' }) },

    /* ──────────────────────────── account ───────────────────────────── */
    {
        file: 'account.html', view: 'account.dashboard', route: 'account.dashboard',
        data: () => ({
            recentOrders: world.demo.orders.take(4),
            stats: {
                orders: world.demo.orders.count(),
                open_orders: world.demo.orders.filter((o) => o.status.isOpen()).count(),
                wishlist: world.demo.wishlists.count(),
                reviews: world.demo.reviews.count(),
                loyalty: world.demo.loyalty_points,
                total_paid: world.demo.orders.sum('grand_total'),
            },
            recentlyViewed: collect(world.products).take(8),
            wishlist: world.demo.wishedProducts.take(4),
        }),
    },
    {
        file: 'account-orders.html', view: 'account.orders', route: 'account.orders.index',
        data: () => ({
            orders: paginate(world.demo.orders, 10, 1),
            counts: {
                all: world.demo.orders.count(),
                open: world.demo.orders.filter((o) => o.status.isOpen()).count(),
                delivered: world.demo.orders.filter((o) => o.status.value === 'delivered').count(),
                cancelled: world.demo.orders.filter((o) => ['cancelled', 'returned'].includes(o.status.value)).count(),
            },
            tab: 'all',
        }),
    },
    { file: 'account-order.html', view: 'account.order-details', route: 'account.orders.show', data: () => ({ order: demoOrder }) },
    { file: 'account-invoice.html', view: 'account.invoice', route: 'account.orders.invoice', data: () => ({ order: demoOrder }) },
    { file: 'account-addresses.html', view: 'account.addresses', route: 'account.addresses', data: () => ({ addresses: world.demo.addresses, provinces: helpers().Iran.provinces() }) },
    { file: 'account-wishlist.html', view: 'account.wishlist', route: 'account.wishlist', data: () => ({ products: paginate(world.demo.wishedProducts, 12, 1) }) },
    { file: 'account-reviews.html', view: 'account.reviews', route: 'account.reviews', data: () => ({ reviews: paginate(world.demo.reviews, 10, 1) }) },
    { file: 'account-recently-viewed.html', view: 'account.recently-viewed', route: 'account.recently-viewed', data: () => ({ products: collect(world.products).take(18) }) },
    { file: 'account-notifications.html', view: 'account.notifications', route: 'account.notifications', data: () => ({ notifications: paginate(world.demo.notifications, 15, 1) }) },
    { file: 'account-payments.html', view: 'account.payments', route: 'account.payments', data: () => ({ orders: paginate(world.demo.orders.filter((o) => o.payment_status.value === 'paid'), 12, 1), totalPaid: world.demo.orders.sum('grand_total') }) },
    { file: 'account-profile.html', view: 'account.profile', route: 'account.profile', data: () => ({ user: world.demo }) },
    {
        file: 'account-security.html', view: 'account.security', route: 'account.security',
        data: () => ({
            user: world.demo,
            sessions: new Collection([
                { id: 's1', ip_address: '5.121.44.10', user_agent: 'Chrome on Windows', last_activity: Math.floor(Date.now() / 1000) - 300 },
                { id: 's2', ip_address: '31.7.88.2', user_agent: 'Safari on iPhone', last_activity: Math.floor(Date.now() / 1000) - 86400 },
            ]),
        }),
    },
    { file: 'account-tickets.html', view: 'account.tickets', route: 'account.tickets.index', data: () => ({ tickets: paginate(world.demo.tickets, 10, 1) }) },
    { file: 'account-ticket-create.html', view: 'account.ticket-create', route: 'account.tickets.create', data: () => ({ orders: world.demo.orders.take(20) }) },
    { file: 'account-ticket.html', view: 'account.ticket-show', route: 'account.tickets.show', data: () => ({ ticket: sampleTicket }) },

    /* ───────────────────────────── admin ────────────────────────────── */
    {
        file: 'admin.html', view: 'admin.dashboard', route: 'admin.dashboard', admin: true,
        data: () => {
            const paid = world.orders.filter((o) => o.payment_status.value === 'paid');
            const revenue = paid.reduce((c, o) => c + o.grand_total, 0);
            return {
                cards: [
                    { key: 'revenue', label: 'درآمد کل', value: S.faNumber(S.toman(revenue)), suffix: 'تومان', icon: 'wallet', trend: 12.4, tone: 'brand' },
                    { key: 'orders', label: 'سفارش‌ها', value: S.faNumber(world.orders.length), suffix: 'سفارش', icon: 'bag', trend: 6.1, tone: 'info' },
                    { key: 'customers', label: 'مشتریان', value: S.faNumber(world.customers.length), suffix: 'کاربر', icon: 'users', trend: 3.8, tone: 'success' },
                    { key: 'products', label: 'کالاها', value: S.faNumber(world.products.length), suffix: 'کالا', icon: 'box', trend: -1.2, tone: 'warning' },
                ],
                chart: { labels: Array.from({ length: 14 }, (_, i) => S.jalali(DateTime.now().subDays(13 - i))), revenue: Array.from({ length: 14 }, () => Math.round(Math.random() * 900) * 1_000_000, ), orders: Array.from({ length: 14 }, () => 3 + Math.round(Math.random() * 18)) },
                recentOrders: new Collection(world.orders.slice(0, 8)),
                topProducts: collect(world.products).sortByDesc('sold_count').take(6),
                lowStock: collect(world.products).filter((p) => p.stock <= 5).take(6),
                latestCustomers: new Collection(world.customers.slice(0, 6)),
                activity: new Collection(world.activity.slice(0, 8)),
                statusBreakdown: OrderStatus.cases().toArray().map((state) => ({
                    status: state,
                    label: state.label(),
                    count: world.orders.filter((o) => o.status.value === state.value).length,
                })),
                todayStats: {
                    orders: 14,
                    revenue: world.orders.slice(0, 14).reduce((c, o) => c + o.grand_total, 0),
                    customers: 6,
                    visits: 4820,
                },
                pending: {
                    orders: world.orders.filter((o) => o.status.value === 'pending').length,
                    processing: world.orders.filter((o) => o.status.value === 'processing').length,
                    reviews: adminBadges.pending_reviews,
                    tickets: adminBadges.open_tickets,
                    low_stock: adminBadges.low_stock,
                    out_of_stock: world.products.filter((p) => p.stock === 0).length,
                },
            };
        },
    },
    {
        file: 'admin-products.html', view: 'admin.products.index', route: 'admin.products.index', admin: true,
        data: () => ({
            products: paginate(collect(world.products), 20, 1),
            categories: new Collection(world.categories),
            brands: new Collection(world.brands),
            counts: {
                all: world.products.length,
                active: world.products.filter((p) => p.is_active).length,
                inactive: 0,
                out: world.products.filter((p) => p.stock === 0).length,
                special: world.products.filter((p) => p.is_special).length,
            },
        }),
    },
    { file: 'admin-product-create.html', view: 'admin.products.create', route: 'admin.products.create', admin: true, data: () => ({ categories: new Collection(world.categories), brands: new Collection(world.brands) }) },
    { file: 'admin-product-edit.html', view: 'admin.products.edit', route: 'admin.products.edit', admin: true, data: () => ({ product: sampleProduct, categories: new Collection(world.categories), brands: new Collection(world.brands) }) },
    {
        file: 'admin-categories.html', view: 'admin.categories.index', route: 'admin.categories.index', admin: true,
        data: () => ({
            categories: new Collection(world.categories.filter((c) => !c.parent_id)),
            flat: new Collection(world.categories),
            total: world.categories.length,
        }),
    },
    { file: 'admin-brands.html', view: 'admin.brands.index', route: 'admin.brands.index', admin: true, data: () => ({ brands: paginate(new Collection(world.brands), 20, 1) }) },
    {
        file: 'admin-orders.html', view: 'admin.orders.index', route: 'admin.orders.index', admin: true,
        data: () => ({
            orders: paginate(new Collection(world.orders), 20, 1),
            counts: Object.fromEntries(OrderStatus.cases().toArray().map((s) => [s.value, world.orders.filter((o) => o.status.value === s.value).length])),
            revenue: {
                total: world.orders.reduce((c, o) => c + o.grand_total, 0),
                today: world.orders.slice(0, 4).reduce((c, o) => c + o.grand_total, 0),
                unpaid: world.orders.filter((o) => o.payment_status.value === 'unpaid').reduce((c, o) => c + o.grand_total, 0),
            },
        }),
    },
    { file: 'admin-order.html', view: 'admin.orders.show', route: 'admin.orders.show', admin: true, data: () => ({ order: world.orders[0] }) },
    { file: 'admin-order-invoice.html', view: 'admin.orders.invoice', route: 'admin.orders.invoice', admin: true, data: () => ({ order: world.orders[0] }) },
    {
        file: 'admin-customers.html', view: 'admin.customers.index', route: 'admin.customers.index', admin: true,
        data: () => ({
            customers: paginate(new Collection(world.customers), 20, 1),
            stats: {
                total: world.customers.length,
                active: world.customers.filter((c) => c.is_active).length,
                new_month: 12,
                with_orders: 28,
            },
        }),
    },
    {
        file: 'admin-customer.html', view: 'admin.customers.show', route: 'admin.customers.show', admin: true,
        data: () => ({
            customer: sampleCustomer,
            orders: paginate(new Collection(world.orders.filter((o) => o.user_id === sampleCustomer.id)), 10, 1),
            totals: { orders: 4, paid: 820_000_000, avg: 205_000_000 },
        }),
    },
    { file: 'admin-coupons.html', view: 'admin.coupons.index', route: 'admin.coupons.index', admin: true, data: () => ({
            coupons: paginate(new Collection(world.coupons), 20, 1),
            categories: new Collection(world.categories),
            stats: {
                total: world.coupons.length,
                active: world.coupons.filter((c) => c.is_active).length,
                used: world.coupons.reduce((c, x) => c + x.used_count, 0),
                expired: world.coupons.filter((c) => !c.is_active).length,
            },
        }) },
    {
        file: 'admin-reviews.html', view: 'admin.reviews.index', route: 'admin.reviews.index', admin: true,
        data: () => ({
            reviews: paginate(new Collection(world.reviews.slice(0, 60)), 20, 1),
            counts: {
                all: world.reviews.length,
                pending: world.reviews.filter((r) => r.status.value === 'pending').length,
                approved: world.reviews.filter((r) => r.status.value === 'approved').length,
                rejected: world.reviews.filter((r) => r.status.value === 'rejected').length,
            },
        }),
    },
    {
        file: 'admin-questions.html', view: 'admin.reviews.questions', route: 'admin.questions.index', admin: true,
        data: () => ({
            questions: paginate(new Collection(world.questions.slice(0, 40)), 20, 1),
            counts: {
                all: world.questions.length,
                pending: world.questions.filter((q) => q.status === 'pending').length,
                approved: world.questions.filter((q) => q.status === 'approved').length,
            },
        }),
    },
    {
        file: 'admin-inventory.html', view: 'admin.inventory.index', route: 'admin.inventory.index', admin: true,
        data: () => ({
            products: paginate(collect(world.products).sortBy('stock'), 20, 1),
            stats: {
                total_units: world.products.reduce((c, p) => c + p.stock, 0),
                stock_value: world.products.reduce((c, p) => c + p.stock * p.price, 0),
                low: world.products.filter((p) => p.stock > 0 && p.stock <= 5).length,
                out: world.products.filter((p) => p.stock === 0).length,
            },
            threshold: 5,
        }),
    },
    {
        file: 'admin-movements.html', view: 'admin.inventory.movements', route: 'admin.inventory.movements', admin: true,
        data: () => ({
            movements: paginate(new Collection(world.movements), 20, 1),
            types: {
                sale: ['فروش', 'brand'], purchase: ['خرید', 'success'],
                adjustment: ['اصلاح', 'info'], return: ['مرجوعی', 'warning'],
            },
        }),
    },
    {
        file: 'admin-banners.html', view: 'admin.banners.index', route: 'admin.banners.index', admin: true,
        data: () => ({
            banners: collect(world.banners).groupBy('position'),
            positions: {
                hero: 'اسلایدر اصلی', 'promo-right': 'بنر تبلیغاتی راست',
                'promo-left': 'بنر تبلیغاتی چپ', strip: 'نوار تبلیغاتی', sidebar: 'بنر ستون کناری',
            },
        }),
    },
    {
        file: 'admin-posts.html', view: 'admin.posts.index', route: 'admin.posts.index', admin: true,
        data: () => ({
            posts: paginate(new Collection(world.posts), 20, 1),
            counts: { all: world.posts.length, published: world.posts.length, draft: 0 },
        }),
    },
    { file: 'admin-post-create.html', view: 'admin.posts.create', route: 'admin.posts.create', admin: true, data: () => ({ categories: new Collection(world.categories) }) },
    { file: 'admin-post-edit.html', view: 'admin.posts.edit', route: 'admin.posts.edit', admin: true, data: () => ({ post: samplePost, categories: new Collection(world.categories) }) },
    { file: 'admin-pages.html', view: 'admin.pages.index', route: 'admin.pages.index', admin: true, data: () => ({ pages: new Collection(world.pages) }) },
    {
        file: 'admin-tickets.html', view: 'admin.tickets.index', route: 'admin.tickets.index', admin: true,
        data: () => ({
            tickets: paginate(new Collection(world.tickets), 20, 1),
            counts: {
                all: world.tickets.length,
                open: world.tickets.filter((t) => t.status === 'open').length,
                answered: world.tickets.filter((t) => t.status === 'answered').length,
                closed: world.tickets.filter((t) => t.status === 'closed').length,
            },
        }),
    },
    { file: 'admin-ticket.html', view: 'admin.tickets.show', route: 'admin.tickets.show', admin: true, data: () => ({ ticket: adminTicket }) },
    { file: 'admin-shipping.html', view: 'admin.shipping.index', route: 'admin.shipping.index', admin: true, data: () => ({ methods: new Collection(world.shippingMethods) }) },
    { file: 'admin-staff.html', view: 'admin.staff.index', route: 'admin.staff.index', admin: true, data: () => ({ staff: new Collection([world.admin, world.manager]), roles: UserRole.options() }) },
    {
        file: 'admin-settings.html', view: 'admin.settings.index', route: 'admin.settings.index', admin: true,
        data: () => ({
            schema: SETTINGS_SCHEMA,
            values: world.settings,
        }),
    },
    {
        file: 'admin-reports.html', view: 'admin.reports.index', route: 'admin.reports.index', admin: true,
        data: () => {
            const paid = world.orders.filter((o) => o.payment_status.value === 'paid');
            return {
                from: DateTime.now().subDays(30),
                to: DateTime.now(),
                summary: {
                    revenue: paid.reduce((c, o) => c + o.grand_total, 0),
                    orders: paid.length,
                    avg: Math.round(paid.reduce((c, o) => c + o.grand_total, 0) / Math.max(1, paid.length)),
                    items: paid.reduce((c, o) => c + o.items_count, 0),
                    discount: paid.reduce((c, o) => c + o.discount_total, 0),
                    shipping: paid.reduce((c, o) => c + o.shipping_cost, 0),
                },
                byCategory: new Collection(world.categories.filter((c) => !c.parent_id).map((c) => ({
                    name: c.name, total: Math.round(Math.random() * 900) * 1_000_000, orders: 3 + Math.round(Math.random() * 40),
                }))),
                topProducts: collect(world.products).sortByDesc('sold_count').take(10),
                searches: new Collection(world.searchTerms),
                chart: {
                    labels: Array.from({ length: 14 }, (_, i) => S.jalali(DateTime.now().subDays(13 - i))),
                    revenue: Array.from({ length: 14 }, () => Math.round(Math.random() * 900) * 1_000_000),
                    orders: Array.from({ length: 14 }, () => 3 + Math.round(Math.random() * 18)),
                },
            };
        },
    },
];

const SETTINGS_SCHEMA = {
    general: {
        site_name: { type: 'string', label: 'نام فروشگاه' },
        site_tagline: { type: 'string', label: 'شعار فروشگاه' },
        site_description: { type: 'text', label: 'توضیح کوتاه (سئو)' },
        support_phone: { type: 'string', label: 'شماره پشتیبانی' },
        support_email: { type: 'string', label: 'ایمیل پشتیبانی' },
        address: { type: 'text', label: 'نشانی' },
        working_hours: { type: 'string', label: 'ساعات کاری' },
    },
    shop: {
        free_shipping_from: { type: 'int', label: 'ارسال رایگان از (ریال)' },
        shipping_cost: { type: 'int', label: 'هزینه ارسال پیش‌فرض (ریال)' },
        low_stock_threshold: { type: 'int', label: 'آستانه هشدار موجودی' },
        max_cart_qty: { type: 'int', label: 'حداکثر تعداد هر کالا در سبد' },
        guest_checkout: { type: 'bool', label: 'اجازه خرید مهمان' },
        auto_approve_reviews: { type: 'bool', label: 'تأیید خودکار دیدگاه‌ها' },
    },
    social: {
        instagram: { type: 'string', label: 'اینستاگرام' },
        telegram: { type: 'string', label: 'تلگرام' },
        linkedin: { type: 'string', label: 'لینکدین' },
        twitter: { type: 'string', label: 'ایکس (توییتر)' },
    },
    maintenance: {
        maintenance_mode: { type: 'bool', label: 'حالت تعمیر و نگهداری' },
        maintenance_message: { type: 'text', label: 'پیام حالت تعمیر' },
    },
};

/* ══════════════════════════════════ build ═════════════════════════════════ */

const blade = new Blade({ viewPath: path.join(ROOT, 'resources/views'), helpers: {} });

function build() {
    fs.mkdirSync(DIST, { recursive: true });

    const failures = [];
    let built = 0;

    PAGES.filter((p) => !process.env.DG_ONLY || p.file === process.env.DG_ONLY).forEach((page) => {
        currentRouteName = page.route;
        currentUser = page.guest ? null : (page.admin ? world.admin : world.demo);

        const base = helpers();
        blade.helpers = base;
        blade.cache.clear();

        const data = Object.assign({
            menuCategories,
            footerPages,
            cartSummary: sharedSummary,
            cartItems: sharedCartItems,
            sidebarCounts: sidebarCounts(),
            adminBadges,
            errors: { any: () => false, has: () => false, first: () => '', all: () => [], count: () => 0 },
        }, page.data());

        try {
            const html = blade.render(page.view, data);
            fs.writeFileSync(path.join(DIST, page.file), withPreviewChrome(html, page));
            built += 1;
        } catch (error) {
            if (process.env.DG_ONLY) throw error;
            failures.push({ page: page.file, view: page.view, message: error.message.split('\n')[0], stack: error.stack });
        }
    });

    writeIndex();

    console.log(`\n✅ ${built}/${PAGES.length} pages rendered → preview/dist`);
    if (failures.length) {
        console.log(`\n❌ ${failures.length} failed:`);
        failures.forEach((f) => console.log(`   • ${f.page} (${f.view})\n     ${f.message}`));
        if (process.env.DG_TRACE) console.log('\n', failures[0].stack);
    }
    return failures.length;
}

/** Injects the little floating "preview map" launcher into every page. */
function withPreviewChrome(html, page) {
    const launcher = `
<div id="dg-preview-bar" style="position:fixed;inset-inline-start:16px;bottom:16px;z-index:9999;font-family:inherit">
  <a href="/pages.html" style="display:inline-flex;align-items:center;gap:6px;background:#23262B;color:#fff;border-radius:999px;padding:8px 14px;font-size:12px;font-weight:700;text-decoration:none;box-shadow:0 8px 24px rgba(0,0,0,.25)">
    فهرست صفحات پیش‌نمایش
  </a>
</div>`;

    return html.replace('</body>', `${launcher}\n</body>`);
}

function writeIndex() {
    const groups = {
        'فروشگاه': PAGES.filter((p) => !p.admin && !p.guest && !p.file.startsWith('account')),
        'حساب کاربری': PAGES.filter((p) => p.file.startsWith('account')),
        'ورود و ثبت‌نام': PAGES.filter((p) => p.guest),
        'پنل مدیریت': PAGES.filter((p) => p.admin),
    };

    const section = (title, pages) => `
    <section class="group">
      <h2>${title}</h2>
      <ul>
        ${pages.map((p) => `<li><a href="/${p.file}"><span>${p.view}</span><code>${p.file}</code></a></li>`).join('')}
      </ul>
    </section>`;

    const html = `<!doctype html>
<html lang="fa" dir="rtl">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>نقشه صفحات — پیش‌نمایش دیجی‌نو</title>
<link rel="stylesheet" href="/css/app.css">
<style>
 body{background:#F7F7F8;margin:0;padding:32px 16px;font-family:Vazirmatn,system-ui,sans-serif;color:#23262B}
 .wrap{max-width:1100px;margin:0 auto}
 h1{font-size:24px;margin:0 0 8px}
 p.lead{color:#6b7280;margin:0 0 28px;font-size:14px;line-height:2}
 .group{background:#fff;border:1px solid #E5E5E7;border-radius:12px;padding:18px 20px;margin-bottom:18px}
 .group h2{font-size:16px;margin:0 0 12px;color:#DC1F36}
 ul{list-style:none;margin:0;padding:0;display:grid;gap:8px;grid-template-columns:repeat(auto-fill,minmax(260px,1fr))}
 a{display:flex;justify-content:space-between;gap:10px;align-items:center;text-decoration:none;color:#23262B;background:#F7F7F8;border:1px solid #E5E5E7;border-radius:8px;padding:10px 12px;font-size:13px;transition:.2s}
 a:hover{background:#FDE8EA;border-color:#EF394E;transform:translateY(-1px)}
 code{font-size:11px;color:#6b7280;direction:ltr}
</style>
</head>
<body>
<div class="wrap">
  <h1>پیش‌نمایش ایستای دیجی‌نو</h1>
  <p class="lead">
    این صفحات با همان فایل‌های Blade پروژه و داده‌های آزمایشی هم‌ارز با Seeder‌ها ساخته شده‌اند تا طراحی بدون نیاز به اجرای PHP قابل بررسی باشد.
    رفتارهای پویا (AJAX، مودال‌ها، فیلترها) در نسخه واقعی لاراول اجرا می‌شوند.
  </p>
  ${Object.entries(groups).map(([title, pages]) => section(title, pages)).join('')}
</div>
</body>
</html>`;

    fs.writeFileSync(path.join(DIST, 'pages.html'), html);
}

if (require.main === module) {
    process.exitCode = build() ? 1 : 0;
}

module.exports = { build };
