/**
 * Fixture "database" for the static preview. It mirrors the Laravel seeders
 * (database/seeders/*) closely enough that every Blade view receives data with
 * the same shape it would get from Eloquent.
 */
'use strict';

const fs = require('fs');
const path = require('path');
const { Collection, Paginator, DateTime, collect, faNumber } = require('./support');

const ROOT = path.resolve(__dirname, '..');
const catalog = JSON.parse(fs.readFileSync(path.join(ROOT, 'preview/data/catalog.json'), 'utf8'));

/* ─────────────────────────────── deterministic RNG ───────────────────────── */

let seed = 20260901;
function rnd() {
    seed = (seed * 1103515245 + 12345) % 2147483648;
    return seed / 2147483648;
}
const rand = (min, max) => min + Math.floor(rnd() * (max - min + 1));
const pick = (arr) => arr[rand(0, arr.length - 1)];
const chance = (percent) => rand(1, 100) <= percent;

/* ──────────────────────────────────── enums ──────────────────────────────── */

function makeEnum(defs) {
    const cases = [];
    const byValue = {};

    Object.entries(defs.cases).forEach(([name, value]) => {
        const instance = {
            name,
            value,
            label: () => defs.label[value],
            badgeClass: () => (defs.badgeClass ? defs.badgeClass[value] : ''),
            icon: () => (defs.icon ? defs.icon[value] : 'info'),
            step: () => (defs.step ? defs.step[value] : 0),
            isOpen: () => (defs.open ? defs.open.includes(value) : true),
            allowedTransitions: () => new Collection((defs.transitions?.[value] || []).map((v) => byValue[v])),
            toString: () => value,
        };
        cases.push(instance);
        byValue[value] = instance;
    });

    const api = {
        cases: () => new Collection(cases),
        from: (v) => byValue[v],
        tryFrom: (v) => byValue[v] || null,
        options: () => new Collection(Object.fromEntries(cases.map((c) => [c.value, c.label()]))),
    };

    cases.forEach((c) => { api[c.name] = c; });
    return api;
}

const OrderStatus = makeEnum({
    cases: { Pending: 'pending', Paid: 'paid', Processing: 'processing', Shipped: 'shipped', Delivered: 'delivered', Cancelled: 'cancelled', Returned: 'returned' },
    label: {
        pending: 'در انتظار پرداخت', paid: 'پرداخت شده', processing: 'در حال پردازش',
        shipped: 'ارسال شده', delivered: 'تحویل شده', cancelled: 'لغو شده', returned: 'مرجوع شده',
    },
    badgeClass: {
        pending: 'bg-warning-50 text-warning-600', paid: 'bg-info-50 text-info-600',
        processing: 'bg-warning-50 text-warning-600', shipped: 'bg-success-50 text-success-600',
        delivered: 'bg-success-50 text-success-600', cancelled: 'bg-brand-50 text-brand-600',
        returned: 'bg-ink-100 text-ink-600',
    },
    icon: { pending: 'clock', paid: 'credit-card', processing: 'box', shipped: 'truck', delivered: 'check-circle', cancelled: 'x-circle', returned: 'rotate-left' },
    step: { pending: 1, paid: 2, processing: 2, shipped: 3, delivered: 4, cancelled: 0, returned: 0 },
    open: ['pending', 'paid', 'processing', 'shipped'],
    transitions: {
        pending: ['paid', 'cancelled'], paid: ['processing', 'cancelled'], processing: ['shipped', 'cancelled'],
        shipped: ['delivered', 'returned'], delivered: ['returned'], cancelled: [], returned: [],
    },
});

const PaymentStatus = makeEnum({
    cases: { Unpaid: 'unpaid', Paid: 'paid', Refunded: 'refunded', Failed: 'failed' },
    label: { unpaid: 'پرداخت نشده', paid: 'پرداخت شده', refunded: 'بازگشت وجه', failed: 'ناموفق' },
    badgeClass: { unpaid: 'bg-ink-100 text-ink-600', paid: 'bg-success-50 text-success-600', refunded: 'bg-info-50 text-info-600', failed: 'bg-brand-50 text-brand-600' },
});

const ReviewStatus = makeEnum({
    cases: { Pending: 'pending', Approved: 'approved', Rejected: 'rejected' },
    label: { pending: 'در انتظار بررسی', approved: 'تأیید شده', rejected: 'رد شده' },
    badgeClass: { pending: 'bg-warning-50 text-warning-600', approved: 'bg-success-50 text-success-600', rejected: 'bg-brand-50 text-brand-600' },
});

const UserRole = makeEnum({
    cases: { Customer: 'customer', Manager: 'manager', Admin: 'admin' },
    label: { customer: 'مشتری', manager: 'مدیر بخش', admin: 'مدیر کل' },
    badgeClass: { customer: 'bg-ink-100 text-ink-600', manager: 'bg-info-50 text-info-600', admin: 'bg-brand-50 text-brand-600' },
});

/* ──────────────────────────────── image pools ────────────────────────────── */

function imagePool() {
    const dir = path.join(ROOT, 'public/images/products');
    const pool = { default: [] };

    if (fs.existsSync(dir)) {
        fs.readdirSync(dir).forEach((file) => {
            const slug = file.replace(/\.[a-z]+$/i, '').replace(/-\d+$/, '');
            const rel = `images/products/${file}`;
            (pool[slug] = pool[slug] || []).push(rel);
            pool.default.push(rel);
        });
    }

    if (!pool.default.length) pool.default.push('images/placeholder-product.svg');
    return pool;
}

const IMAGES = imagePool();

/* ─────────────────────────────────── people ──────────────────────────────── */

const FIRST = ['علی', 'محمد', 'رضا', 'حسین', 'امیر', 'مهدی', 'سعید', 'نیما', 'سارا', 'زهرا', 'مریم', 'نگین', 'الهام', 'شیما', 'پریسا', 'نازنین', 'یاسمن', 'آرش', 'کیانا', 'بهار'];
const LAST = ['محمدی', 'حسینی', 'رضایی', 'کریمی', 'موسوی', 'احمدی', 'صادقی', 'جعفری', 'قاسمی', 'نوروزی', 'شریفی', 'اکبری', 'مرادی', 'زارعی', 'یوسفی'];
const PROVINCES = { 'تهران': ['تهران', 'اسلامشهر', 'شهریار', 'ری'], 'البرز': ['کرج', 'فردیس'], 'اصفهان': ['اصفهان', 'کاشان'], 'فارس': ['شیراز', 'مرودشت'], 'خراسان رضوی': ['مشهد', 'نیشابور'] };

/* ─────────────────────────────────── builders ────────────────────────────── */

let nextId = 1;
const id = () => nextId++;

function buildWorld() {
    /* -------------------------------------------------------- categories */
    const categories = [];
    const categoryBySlug = {};

    catalog.categories.forEach((root, i) => {
        const parent = {
            id: id(), parent_id: null, name: root.name, slug: root.slug, icon: root.icon,
            description: `خرید اینترنتی انواع ${root.name} با بهترین قیمت و ضمانت اصالت کالا از دیجی‌نو.`,
            is_active: true, show_in_menu: true, sort_order: i, image: null,
            products_count: 0, children: new Collection([]), parent: null,
        };
        categories.push(parent);
        categoryBySlug[parent.slug] = parent;

        (root.children || []).forEach((child, j) => {
            const node = {
                id: id(), parent_id: parent.id, name: child.name, slug: child.slug, icon: child.icon,
                description: `جدیدترین مدل‌های ${child.name} با قیمت روز و ضمانت دیجی‌نو.`,
                is_active: true, show_in_menu: true, sort_order: j, image: null,
                products_count: 0, children: new Collection([]), parent,
            };
            parent.children.push(node);
            categories.push(node);
            categoryBySlug[node.slug] = node;
        });
    });

    categories.forEach((c) => {
        c.breadcrumbTrail = () => new Collection(c.parent ? [c.parent, c] : [c]);
        c.url = `/category-${c.slug}.html`;
    });

    /* ------------------------------------------------------------ brands */
    const brands = catalog.brands.map((b, i) => ({
        id: id(), name: b.name, name_en: b.name_en, slug: b.slug,
        logo: fs.existsSync(path.join(ROOT, `public/images/brands/${b.slug}.svg`)) ? `images/brands/${b.slug}.svg` : null,
        description: `کالاهای اصل ${b.name} با ضمانت اصالت، در دیجی‌نو.`,
        is_featured: !!b.featured, is_active: true, sort_order: i, products_count: 0,
    }));
    const brandBySlug = Object.fromEntries(brands.map((b) => [b.slug, b]));

    /* ------------------------------------------------- shipping methods */
    const shippingMethods = catalog.shipping.map((m, i) => ({
        id: id(), ...m, sort_order: i, is_active: true,
        costFor: (total) => (m.free_from && total >= m.free_from ? 0 : m.cost),
    }));

    /* ---------------------------------------------------------- products */
    const products = [];

    Object.entries(catalog.products).forEach(([categorySlug, items]) => {
        const category = categoryBySlug[categorySlug];
        if (!category) return;

        items.forEach((item) => products.push(makeProduct(item, category, brandBySlug)));
    });

    // A little extra volume so grids and pagination look real.
    const fillerNames = {
        'mobile-accessory': ['قاب محافظ گوشی مدل %s', 'محافظ صفحه نمایش مدل %s', 'کابل تبدیل USB-C مدل %s', 'شارژر فندکی مدل %s'],
        'headphone': ['هندزفری بلوتوثی مدل %s', 'اسپیکر قابل حمل مدل %s'],
        'kitchen-appliance': ['چای‌ساز مدل %s', 'همزن برقی مدل %s', 'توستر نان مدل %s'],
        'stationery': ['خودکار روان‌نویس مدل %s', 'دفتر ۸۰ برگ طرح %s'],
        'sport-gear': ['کش مقاومتی مدل %s', 'قمقمه ورزشی مدل %s'],
        'tshirt': ['تی‌شرت نخی طرح %s', 'هودی کلاه‌دار مدل %s'],
        'sneakers': ['کفش پیاده‌روی مدل %s', 'کتانی روزمره مدل %s'],
        'grocery': ['چای سیاه ممتاز %s', 'عسل طبیعی %s'],
        'book': ['کتاب %s (چاپ جدید)', 'رمان %s'],
        'tools': ['اره عمودبر مدل %s', 'متر لیزری مدل %s'],
        'car-accessory': ['کفپوش سه‌بعدی مدل %s', 'دوربین ثبت وقایع مدل %s'],
        'smart-watch': ['مچ‌بند سلامتی مدل %s', 'بند سیلیکونی ساعت مدل %s'],
    };
    const codes = ['A1', 'B3', 'C5', 'D7', 'E9', 'K8', 'M5', 'R1', 'T6', 'X2'];

    Object.entries(fillerNames).forEach(([slug, patterns]) => {
        const category = categoryBySlug[slug];
        if (!category) return;

        patterns.forEach((pattern) => {
            for (let n = 0; n < 3; n++) {
                const price = rand(3, 90) * 100_000;
                products.push(makeProduct({
                    name: pattern.replace('%s', pick(codes)),
                    brand: pick(Object.keys(brandBySlug)),
                    price,
                    discount: pick([0, 0, 6, 11, 15, 22, 30]),
                    subtitle: 'ارسال سریع از انبار دیجی‌نو',
                    warranty: '۱۲ ماه ضمانت اصالت و سلامت کالا',
                    highlights: ['بسته‌بندی استاندارد', 'ضمانت اصالت کالا', 'بازگشت تا ۷ روز'],
                    specs: { 'مشخصات': { 'گارانتی': 'اصالت کالا' } },
                }, category, brandBySlug));
            }
        });
    });

    products.forEach((p) => { p.category.products_count += 1; if (p.brand) p.brand.products_count += 1; });

    /* ------------------------------------------------------------- users */
    const users = [];
    const admin = makeUser({ name: 'یارمحمدی', email: 'admin@digino.test', mobile: '09120000001', role: 'admin' });
    const manager = makeUser({ name: 'نگین شریفی', email: 'manager@digino.test', mobile: '09120000002', role: 'manager' });
    const demo = makeUser({ name: 'سارا محمدی', email: 'user@digino.test', mobile: '09120000003', role: 'customer', loyalty_points: 1840 });
    users.push(admin, manager, demo);

    for (let i = 0; i < 40; i++) {
        users.push(makeUser({
            name: `${pick(FIRST)} ${pick(LAST)}`,
            email: `customer${i}@digino.test`,
            mobile: `09${rand(10, 39)}${rand(1000000, 9999999)}`,
            role: 'customer',
            created_at: DateTime.now().subDays(rand(1, 400)),
        }));
    }

    const customers = users.filter((u) => u.role.value === 'customer');
    demo.addresses = new Collection([makeAddress(demo, 'منزل', true), makeAddress(demo, 'محل کار', false)]);
    customers.forEach((c) => { if (c.addresses.isEmpty()) c.addresses = new Collection([makeAddress(c, 'منزل', true)]); });

    /* ------------------------------------------------------------ orders */
    const orders = [];
    for (let i = 0; i < 60; i++) {
        orders.push(makeOrder(i, pick(customers), products, shippingMethods));
    }
    // a couple of orders that always belong to the demo account
    for (let i = 0; i < 6; i++) orders.push(makeOrder(100 + i, demo, products, shippingMethods));

    orders.forEach((o) => { o.user.orders_count = (o.user.orders_count || 0) + 1; });

    /* ----------------------------------------------------------- reviews */
    const reviews = [];
    const questions = [];

    products.forEach((product) => {
        const count = rand(0, 6);
        for (let i = 0; i < count; i++) reviews.push(makeReview(product, pick(customers)));

        const qCount = rand(0, 2);
        for (let i = 0; i < qCount; i++) questions.push(makeQuestion(product, pick(customers), manager));

        const approved = reviews.filter((r) => r.product_id === product.id && r.status.value === 'approved');
        product.reviews_count = approved.length;
        product.questions_count = questions.filter((q) => q.product_id === product.id).length;
        product.rating = approved.length
            ? Math.round((approved.reduce((c, r) => c + r.rating, 0) / approved.length) * 10) / 10
            : 0;
        product.reviews = new Collection(approved);
        product.questions = new Collection(questions.filter((q) => q.product_id === product.id));
    });

    /* ----------------------------------------------------------- content */
    const banners = makeBanners();
    const posts = makePosts(categoryBySlug, admin);
    const pages = makePages();
    const coupons = makeCoupons();
    const tickets = customers.slice(0, 14).map((c, i) => makeTicket(i, c, manager, orders));
    demo.tickets = new Collection([makeTicket(90, demo, manager, orders), makeTicket(91, demo, manager, orders)]);

    const settings = makeSettings();

    /* --------------------------------------------------------- relations */
    demo.wishlists = new Collection(collect(products).shuffle().take(6).toArray().map((p) => ({
        id: id(), user_id: demo.id, product_id: p.id, product: p, created_at: DateTime.now().subDays(rand(1, 40)),
    })));
    demo.wishedProducts = demo.wishlists.map((w) => w.product);
    demo.orders = new Collection(orders.filter((o) => o.user_id === demo.id));
    demo.reviews = new Collection(reviews.filter((r) => r.user_id === demo.id).slice(0, 6));
    if (demo.reviews.isEmpty()) demo.reviews = new Collection(collect(products).take(4).toArray().map((p) => makeReview(p, demo)));
    demo.notifications = new Collection(makeNotifications());
    demo.unreadNotifications = demo.notifications.filter((n) => !n.read_at);

    const searchTerms = ['گوشی سامسونگ', 'آیفون ۱۵', 'لپ تاپ ایسوس', 'هدفون بی سیم', 'ساعت هوشمند', 'ماشین لباسشویی', 'جاروبرقی', 'کفش نایک', 'عطر مردانه', 'پاوربانک']
        .map((term) => ({ term, total: rand(20, 900), results_count: rand(3, 60) }));

    const activity = collect(products).take(14).toArray().map((p) => ({
        id: id(), user: pick([admin, manager]), action: pick(['product.created', 'product.updated', 'order.status', 'coupon.created']),
        subject_type: 'App\\Models\\Product', subject_id: p.id,
        description: `کالای «${p.name}» به‌روزرسانی شد.`,
        ip: `192.168.1.${rand(2, 250)}`, created_at: DateTime.now().subDays(rand(0, 20)),
    }));

    const movements = collect(products).take(24).toArray().map((p) => {
        const type = pick(['sale', 'purchase', 'adjustment', 'return']);
        const qty = type === 'sale' ? -rand(1, 4) : rand(1, 20);
        return {
            id: id(), product: p, product_id: p.id, variant: null, product_variant: null,
            user: pick([admin, manager]), type, quantity: qty, stock_after: Math.max(0, p.stock),
            reference: type === 'sale' ? pick(orders).code : null,
            note: type === 'adjustment' ? 'اصلاح موجودی پس از انبارگردانی' : null,
            created_at: DateTime.now().subDays(rand(0, 30)),
        };
    });

    const subscribers = Array.from({ length: 24 }, (_, i) => ({
        id: id(), email: `subscriber${i + 1}@example.com`, is_active: chance(92), created_at: DateTime.now().subDays(rand(1, 200)),
    }));

    return {
        categories, categoryBySlug, brands, brandBySlug, shippingMethods, products,
        users, customers, admin, manager, demo, orders, reviews, questions,
        banners, posts, pages, coupons, tickets, settings, searchTerms, activity,
        movements, subscribers,
        enums: { OrderStatus, PaymentStatus, ReviewStatus, UserRole },
    };
}

/* ───────────────────────────────── factories ─────────────────────────────── */

function makeProduct(item, category, brandBySlug) {
    const price = item.price;
    const discount = item.discount || 0;
    const compare = discount ? Math.round(price / (1 - discount / 100)) : null;
    const stock = pick([0, rand(1, 5), rand(6, 40), rand(6, 120), rand(6, 120)]);
    const pool = IMAGES[category.slug] || IMAGES.default;
    const images = collect(pool).shuffle().take(Math.min(pool.length, rand(2, 4)));

    const product = {
        id: id(),
        category, category_id: category.id,
        brand: brandBySlug[item.brand] || null,
        brand_id: (brandBySlug[item.brand] || {}).id || null,
        name: item.name,
        name_en: item.name_en || null,
        slug: `p-${nextId}`,
        sku: `DG-${String(nextId).padStart(6, '0')}`,
        subtitle: item.subtitle || null,
        short_description: item.subtitle || null,
        description: [
            `${item.name} یکی از گزینه‌های محبوب دیجی‌نو در دسته خود است و برای کسانی طراحی شده که به دنبال ترکیبی از کیفیت ساخت، کارایی روزمره و قیمت منصفانه هستند.`,
            item.subtitle || '',
            'تمام کالاهای دیجی‌نو پیش از ارسال از نظر سلامت فیزیکی و اصالت بررسی می‌شوند و در صورت مغایرت، امکان بازگشت کالا تا هفت روز پس از تحویل فراهم است.',
        ].filter(Boolean).join('\n\n'),
        price,
        compare_at_price: compare,
        discount_percent: discount,
        stock,
        max_per_order: rand(2, 5),
        warranty: item.warranty || '۱۲ ماه گارانتی',
        shipping_weight: rand(200, 8000),
        highlights: new Collection(item.highlights || []),
        rating: 0,
        reviews_count: 0,
        questions_count: 0,
        sold_count: rand(0, 1400),
        views_count: rand(120, 48000),
        is_active: true,
        is_featured: chance(24),
        is_special: chance(16),
        is_digino_seller: chance(70),
        has_pickup: chance(35),
        free_shipping: price >= 200_000_000 || chance(15),
        special_ends_at: null,
        created_at: DateTime.now().subDays(rand(1, 300)),
        updated_at: DateTime.now().subDays(rand(0, 20)),
    };

    product.slug = slugify(item.name_en || item.name, product.id);
    product.special_ends_at = product.is_special ? DateTime.now().addHours(rand(5, 60)) : null;

    product.images = images.map((path_, i) => ({
        id: id(), product_id: product.id, path: path_, alt: product.name, is_primary: i === 0, sort_order: i,
    }));
    product.primary_image = product.images.first()?.path || 'images/placeholder-product.svg';

    product.variants = new Collection(makeVariants(item, product));
    product.attributes = new Collection(makeAttributes(item, product));
    product.reviews = new Collection([]);
    product.questions = new Collection([]);

    product.has_discount = discount > 0;
    product.final_price = price;
    product.discount_amount = compare ? compare - price : 0;
    product.is_available = stock > 0;
    product.in_stock = stock > 0;
    product.stock_label = stock <= 0 ? 'ناموجود' : (stock <= 5 ? `تنها ${faNumber(stock)} عدد در انبار باقی مانده` : 'موجود در انبار دیجی‌نو');
    product.url = `/product.html`;
    product.seller_name = product.is_digino_seller ? 'دیجی‌نو' : 'فروشگاه همکار';

    return product;
}

function makeVariants(item, product) {
    const out = [];
    const colors = item.colors || {};
    const option = item.options || null;
    let sort = 0;

    const push = (data) => {
        out.push(Object.assign({
            id: id(), product_id: product.id, title: '', color_name: null, color_hex: null,
            option_name: null, option_value: null, price_diff: 0, stock: rand(0, 25),
            sku: `V-${String(id()).padStart(8, '0')}`, is_active: true, sort_order: sort++,
        }, data));
    };

    if (Object.keys(colors).length && option) {
        Object.entries(colors).forEach(([colorName, hex]) => {
            Object.entries(option.values).forEach(([value, diff]) => {
                push({ title: `${colorName} / ${value}`, color_name: colorName, color_hex: hex, option_name: option.name, option_value: value, price_diff: diff });
            });
        });
        return out;
    }

    Object.entries(colors).forEach(([colorName, hex]) => push({ title: colorName, color_name: colorName, color_hex: hex }));

    if (!Object.keys(colors).length && option) {
        Object.entries(option.values).forEach(([value, diff]) => push({ title: value, option_name: option.name, option_value: value, price_diff: diff }));
    }

    return out;
}

function makeAttributes(item, product) {
    const out = [];
    let sort = 0;

    Object.entries(item.specs || {}).forEach(([group, rows]) => {
        Object.entries(rows).forEach(([name, value]) => {
            out.push({ id: id(), product_id: product.id, group, name, value, is_key: sort < 4, sort_order: sort++ });
        });
    });

    return out;
}

function slugify(text, suffix) {
    const base = String(text).trim().toLowerCase()
        .replace(/[^a-z0-9\u0600-\u06FF]+/g, '-')
        .replace(/^-+|-+$/g, '');
    return `${base || 'product'}-${suffix}`;
}

function makeUser(data) {
    const role = UserRole.tryFrom(data.role || 'customer');
    const user = {
        id: id(),
        name: data.name,
        email: data.email,
        mobile: data.mobile,
        role,
        avatar: null,
        national_code: null,
        birthday: null,
        gender: pick(['male', 'female', null]),
        is_active: data.is_active !== undefined ? data.is_active : chance(95),
        newsletter: chance(45),
        loyalty_points: data.loyalty_points ?? rand(0, 3200),
        email_verified_at: chance(80) ? DateTime.now().subDays(rand(1, 200)) : null,
        mobile_verified_at: DateTime.now().subDays(rand(1, 200)),
        last_login_at: DateTime.now().subDays(rand(0, 20)),
        last_login_ip: `5.${rand(50, 250)}.${rand(1, 250)}.${rand(1, 250)}`,
        created_at: data.created_at || DateTime.now().subDays(rand(20, 500)),
        orders_count: 0,
        orders_sum_grand_total: rand(0, 40) * 50_000_000,
    };

    user.initials = user.name.split(' ').map((p) => p[0]).slice(0, 2).join('');
    user.isAdmin = () => role.value !== 'customer';
    user.isSuperAdmin = () => role.value === 'admin';
    user.addresses = new Collection([]);
    user.orders = new Collection([]);
    user.wishlists = new Collection([]);
    user.wishedProducts = new Collection([]);
    user.reviews = new Collection([]);
    user.tickets = new Collection([]);
    user.notifications = new Collection([]);
    user.unreadNotifications = new Collection([]);
    user.relationLoaded = () => true;

    return user;
}

function makeAddress(user, label, isDefault) {
    const province = pick(Object.keys(PROVINCES));
    return {
        id: id(), user_id: user.id, label,
        receiver_name: user.name, receiver_mobile: user.mobile,
        province, city: pick(PROVINCES[province]),
        line: `خیابان ${pick(['شریعتی', 'ولیعصر', 'آزادی', 'انقلاب', 'فردوسی'])}، کوچه ${rand(1, 40)}، ساختمان ${pick(['نیلوفر', 'ارغوان', 'یاس', 'بهار'])}`,
        plate: String(rand(1, 220)), unit: String(rand(1, 14)),
        postal_code: String(rand(1000000000, 9999999999)),
        is_default: isDefault,
        full: null,
    };
}

function makeOrder(index, user, products, shippingMethods) {
    const status = pick(OrderStatus.cases().toArray());
    const created = DateTime.now().subDays(rand(0, 100)).subHours(rand(0, 20));
    const lines = collect(products).shuffle().take(rand(1, 3)).toArray();

    let itemsTotal = 0;
    const items = lines.map((product) => {
        const qty = rand(1, 3);
        const unit = product.price;
        itemsTotal += unit * qty;
        return {
            id: id(), product, product_id: product.id, product_variant_id: null,
            name: product.name, variant_title: product.variants.first()?.title || null,
            image: product.primary_image, unit_price: unit, discount: 0, quantity: qty,
            line_total: unit * qty,
        };
    });

    const method = pick(shippingMethods);
    const shipping = method.free_from && itemsTotal >= method.free_from ? 0 : method.cost;
    const discountTotal = Math.round(itemsTotal * 0.04);
    const grand = itemsTotal - discountTotal + shipping;
    const paid = !['pending', 'cancelled'].includes(status.value);
    const address = user.addresses.first() || makeAddress(user, 'منزل', true);

    const order = {
        id: id(),
        code: `DG${DateTime.now().format('Ym')}${String(1000 + index).slice(-4)}`,
        user, user_id: user.id,
        status,
        payment_status: paid ? PaymentStatus.Paid : PaymentStatus.Unpaid,
        payment_method: pick(['online', 'online', 'cod', 'wallet']),
        payment_method_label: 'پرداخت اینترنتی',
        transaction_ref: paid ? `DGP-${String(rand(10000000, 99999999))}` : null,
        paid_at: paid ? created.addHours(1) : null,
        items_total: itemsTotal,
        discount_total: discountTotal,
        coupon_discount: 0,
        shipping_cost: shipping,
        tax_total: 0,
        grand_total: grand,
        coupon: null, coupon_id: null,
        shipping_method: method, shipping_method_id: method.id,
        receiver_name: address.receiver_name,
        receiver_mobile: address.receiver_mobile,
        province: address.province, city: address.city,
        address_line: address.line, postal_code: address.postal_code,
        tracking_code: ['shipped', 'delivered'].includes(status.value) ? String(rand(100000000, 999999999)) + String(rand(100000000, 999999999)) : null,
        customer_note: chance(20) ? 'لطفاً پیش از ارسال تماس بگیرید.' : null,
        admin_note: null,
        created_at: created,
        updated_at: created.addDays(rand(0, 4)),
        shipped_at: ['shipped', 'delivered'].includes(status.value) ? created.addDays(1) : null,
        delivered_at: status.value === 'delivered' ? created.addDays(rand(2, 5)) : null,
        cancelled_at: status.value === 'cancelled' ? created.addHours(rand(2, 40)) : null,
    };

    order.items = new Collection(items);
    order.items_count = items.reduce((c, i) => c + i.quantity, 0);
    order.is_payable = status.value === 'pending';
    order.can_cancel = ['pending', 'paid'].includes(status.value);
    order.statusLogs = new Collection(buildTrail(order, user));
    order.status_logs = order.statusLogs;
    order.full_address = `${order.province}، ${order.city}، ${order.address_line}`;

    return order;
}

function buildTrail(order, user) {
    const chains = {
        pending: ['pending'],
        cancelled: ['pending', 'cancelled'],
        paid: ['pending', 'paid'],
        processing: ['pending', 'paid', 'processing'],
        shipped: ['pending', 'paid', 'processing', 'shipped'],
        delivered: ['pending', 'paid', 'processing', 'shipped', 'delivered'],
        returned: ['pending', 'paid', 'processing', 'shipped', 'delivered', 'returned'],
    };

    let stamp = order.created_at;
    let previous = null;

    return chains[order.status.value].map((value) => {
        const log = {
            id: id(), order_id: order.id, user,
            from_status: previous ? OrderStatus.tryFrom(previous) : null,
            to_status: OrderStatus.tryFrom(value),
            note: null,
            created_at: stamp,
        };
        previous = value;
        stamp = stamp.addHours(rand(3, 26));
        return log;
    });
}

const REVIEW_TITLES = ['کاملاً راضی هستم', 'ارزش خرید بالا', 'نسبت به قیمتش عالیه', 'کیفیت ساخت خوب', 'بسته‌بندی و ارسال عالی', 'پیشنهاد می‌کنم'];
const REVIEW_BODIES = [
    'بعد از حدود یک ماه استفاده می‌توانم بگویم انتخاب درستی بوده؛ کیفیت ساخت در حد قیمت است و تا امروز مشکلی نداشته‌ام. ارسال هم سریع‌تر از چیزی بود که فکر می‌کردم.',
    'کالا دقیقاً مطابق توضیحات سایت بود و سالم به دستم رسید. بسته‌بندی بسیار خوب انجام شده بود.',
    'برای کاربری روزمره کاملاً جواب می‌دهد؛ اگر استفاده حرفه‌ای مدنظرتان است، مدل بالاتر را بررسی کنید.',
    'قیمتش در مقایسه با فروشگاه‌های دیگر منصفانه بود و پشتیبانی هم سریع پاسخ داد.',
];

function makeReview(product, user) {
    const rating = pick([5, 5, 5, 4, 4, 3, 2]);
    const status = pick([ReviewStatus.Approved, ReviewStatus.Approved, ReviewStatus.Approved, ReviewStatus.Pending, ReviewStatus.Rejected]);

    return {
        id: id(), product, product_id: product.id, user, user_id: user.id, order: null, order_id: null,
        title: pick(REVIEW_TITLES), body: pick(REVIEW_BODIES), rating,
        pros: new Collection(collect(['کیفیت ساخت', 'قیمت مناسب', 'ارسال سریع', 'طراحی زیبا']).shuffle().take(rand(1, 3)).toArray()),
        cons: new Collection(rating <= 3 ? ['نبود دفترچه فارسی'] : []),
        recommends: rating >= 4,
        status,
        reject_reason: status.value === 'rejected' ? 'متن دیدگاه با قوانین انتشار دیجی‌نو مطابقت نداشت.' : null,
        is_buyer: chance(70),
        likes: rand(0, 120), dislikes: rand(0, 20),
        created_at: DateTime.now().subDays(rand(1, 180)),
    };
}

function makeQuestion(product, user, staff) {
    const body = pick([
        'سلام، این کالا اصل و دارای گارانتی معتبر است؟',
        'ارسال به شهرستان چند روز طول می‌کشد؟',
        'امکان پرداخت در محل برای این کالا وجود دارد؟',
        'آیا این مدل با لوازم جانبی نسل قبل سازگار است؟',
    ]);
    const created = DateTime.now().subDays(rand(1, 100));

    const question = {
        id: id(), product, product_id: product.id, user, user_id: user.id,
        body, status: chance(78) ? 'approved' : 'pending', created_at: created,
    };

    question.answers = new Collection(chance(75) ? [{
        id: id(), question_id: question.id, user: staff, is_staff: true, status: 'approved',
        body: 'با سلام و احترام؛ بله، تمام کالاهای دیجی‌نو اصل بوده و با ضمانت اصالت و سلامت فیزیکی ارسال می‌شوند.',
        created_at: created.addHours(rand(2, 20)),
    }] : []);
    question.approvedAnswers = question.answers;
    question.answers_count = question.answers.count();

    return question;
}

function makeBanners() {
    const hero = [
        ['جشنواره کالای دیجیتال', 'تا ۴۵٪ تخفیف روی منتخب موبایل و لپ‌تاپ', 'مشاهده تخفیف‌ها', '#FDE8EA'],
        ['خانه‌ای تازه با دیجی‌نو', 'لوازم خانگی برندهای معتبر با پرداخت در محل', 'خرید لوازم خانگی', '#E7F2FF'],
        ['شگفت‌انگیزهای امروز', 'قیمت‌های ویژه فقط تا پایان امروز', 'رفتن به شگفت‌انگیزها', '#FFF3DA'],
        ['استایل فصل جدید', 'کالکشن مد و پوشاک با ارسال رایگان', 'دیدن کالکشن', '#E9F7EF'],
    ].map(([title, subtitle, cta, bg], i) => ({
        id: id(), title, subtitle, caption: null, cta_label: cta,
        image: `images/banners/hero-${i + 1}.jpg`, mobile_image: `images/banners/hero-${i + 1}.jpg`,
        link: null, position: 'hero', bg_color: bg, is_active: true, sort_order: i,
        starts_at: DateTime.now().subDays(10), ends_at: DateTime.now().addMonths(3),
    }));

    const promos = [
        ['هدفون و هندزفری', 'شروع قیمت از ۴۵۰ هزار تومان', 'promo-right'],
        ['ساعت هوشمند', 'تا ۳۰٪ تخفیف', 'promo-left'],
        ['کالای سوپرمارکتی', 'ارسال یک‌روزه در تهران', 'promo-right'],
        ['کتاب و لوازم‌التحریر', 'با کد BOOKLOVER', 'promo-left'],
    ].map(([title, caption, position], i) => ({
        id: id(), title, subtitle: null, caption, cta_label: null,
        image: `images/banners/promo-${i + 1}.jpg`, mobile_image: null, link: null,
        position, bg_color: null, is_active: true, sort_order: i,
        starts_at: DateTime.now().subDays(10), ends_at: DateTime.now().addMonths(3),
    }));

    const strip = [{
        id: id(), title: 'ارسال رایگان برای سفارش‌های بالای ۵۰۰ هزار تومان', subtitle: null,
        caption: 'در تمام شهرهای تحت پوشش دیجی‌نو', cta_label: null,
        image: 'images/banners/strip-1.jpg', mobile_image: null, link: null,
        position: 'strip', bg_color: null, is_active: true, sort_order: 0,
        starts_at: null, ends_at: null,
    }];

    return [...hero, ...promos, ...strip];
}

function makePosts(categoryBySlug, author) {
    const articles = [
        ['digital', 'buying-guide-mobile-1405', 'راهنمای خرید گوشی موبایل در سال ۱۴۰۵', 'قبل از خرید گوشی، این پنج معیار را بررسی کنید تا انتخابی داشته باشید که چند سال همراهتان بماند.'],
        ['digital', 'gaming-vs-work-laptop', 'لپ‌تاپ گیمینگ یا لپ‌تاپ کاری؟ کدام مناسب شماست', 'تفاوت پردازنده گرافیکی، سیستم خنک‌کننده و وزن، انتخاب بین این دو دسته را روشن می‌کند.'],
        ['home-appliance', 'washing-machine-capacity', 'چگونه ظرفیت مناسب ماشین لباسشویی را انتخاب کنیم', 'ظرفیت، دور موتور و مصرف انرژی سه عاملی هستند که هزینه بلندمدت شما را تعیین می‌کنند.'],
        ['digital', 'battery-life-tips', 'ده نکته برای افزایش عمر باتری گوشی', 'عادت‌های ساده شارژ می‌توانند سلامت باتری را تا دو برابر طولانی‌تر کنند.'],
        ['fashion', 'choosing-sport-shoes', 'راهنمای انتخاب کفش ورزشی بر اساس نوع تمرین', 'کفش دویدن، تمرین در باشگاه و پیاده‌روی روزمره ساختار متفاوتی دارند.'],
        ['supermarket', 'monthly-grocery-tips', 'چطور خرید ماهانه خانوار را بهینه کنیم', 'با فهرست‌نویسی و خرید عمده اقلام پرمصرف، می‌توان تا ۲۰٪ صرفه‌جویی کرد.'],
        ['book-stationery', 'best-selling-books', 'شش کتاب پرفروش امسال که ارزش خواندن دارند', 'مروری کوتاه بر عناوینی که بیشترین بازخورد را از خوانندگان دیجی‌نو گرفته‌اند.'],
        ['digital', 'oled-amoled-ips', 'تفاوت پنل‌های OLED، AMOLED و IPS به زبان ساده', 'کیفیت رنگ، مصرف انرژی و ماندگاری تصویر در این سه فناوری متفاوت است.'],
    ];

    return articles.map(([categorySlug, slug, title, excerpt], i) => ({
        id: id(), user: author, user_id: author.id,
        category: categoryBySlug[categorySlug] || null,
        category_id: (categoryBySlug[categorySlug] || {}).id || null,
        title, slug, excerpt,
        body: [
            excerpt,
            'چرا این موضوع مهم است؟',
            'خرید درست از شناخت نیاز واقعی شروع می‌شود. پیش از مقایسه مدل‌ها، مشخص کنید بیشترین استفاده شما از کالا در چه شرایطی است؛ همین یک تصمیم، فهرست گزینه‌های پیش رو را به شکل قابل توجهی کوتاه می‌کند.',
            'مهم‌ترین معیارها',
            'در بررسی‌های کارشناسان دیجی‌نو، سه معیار بیشترین تأثیر را بر رضایت بلندمدت خریداران داشته‌اند: کیفیت ساخت و دوام قطعات، هزینه نگهداری و در دسترس بودن خدمات پس از فروش.',
            'جمع‌بندی',
            'اگر پس از مطالعه این راهنما همچنان بین چند مدل مردد هستید، از ابزار مقایسه دیجی‌نو استفاده کنید و دیدگاه خریداران واقعی را بخوانید.',
        ].join('\n\n'),
        cover: `images/blog/blog-${(i % 6) + 1}.jpg`,
        read_minutes: rand(3, 11),
        views_count: rand(200, 18000),
        is_published: true,
        published_at: DateTime.now().subDays(rand(2, 180)),
        created_at: DateTime.now().subDays(rand(2, 180)),
    }));
}

function makePages() {
    return [
        ['شرایط ارسال و تحویل سفارش', 'shipping-guide', true],
        ['رویه بازگرداندن کالا', 'return-policy', true],
        ['ضمانت اصالت و سلامت کالا', 'authenticity', true],
        ['فروش در دیجی‌نو', 'sell-with-us', true],
        ['فرصت‌های شغلی', 'careers', false],
        ['حریم خصوصی داده‌های پرداخت', 'payment-security', false],
    ].map(([title, slug, inFooter], i) => ({
        id: id(), title, slug, in_footer: inFooter, is_published: true, sort_order: i,
        body: [
            'سفارش‌های ثبت‌شده تا ساعت ۱۴ در روزهای کاری، همان روز پردازش و تحویل شرکت حمل می‌شوند.',
            'برای سفارش‌های بالای ۵۰۰ هزار تومان هزینه ارسال رایگان است. در غیر این صورت هزینه ارسال بر اساس روش انتخابی شما در صفحه پرداخت محاسبه و نمایش داده می‌شود.',
            'در صورت مشاهده هرگونه مغایرت، پشتیبانی دیجی‌نو در ساعات کاری پاسخگوی شماست.',
        ].join('\n\n'),
        updated_at: DateTime.now().subDays(rand(2, 60)),
        created_at: DateTime.now().subDays(rand(60, 200)),
    }));
}

function makeCoupons() {
    return [
        ['DIGINO10', '۱۰٪ تخفیف خوش‌آمدگویی برای اولین خرید', 'percent', 10, 3_000_000, 5_000_000, 2000, 318, true],
        ['MOBILE15', '۱۵٪ تخفیف ویژه موبایل', 'percent', 15, 8_000_000, 10_000_000, 500, 96, true],
        ['FREESHIP', 'کوپن ۴۹ هزار تومانی هزینه ارسال', 'fixed', 49_000, null, 2_000_000, null, 742, true],
        ['NOWRUZ', 'جشنواره نوروزی — ۲۰٪ تخفیف', 'percent', 20, 12_000_000, 15_000_000, 300, 300, false],
        ['HOME25', '۲۵۰ هزار تومان تخفیف لوازم خانگی', 'fixed', 250_000, null, 25_000_000, 400, 51, true],
        ['BOOKLOVER', '۱۲٪ تخفیف کتاب و لوازم‌التحریر', 'percent', 12, 1_000_000, 500_000, 1000, 187, true],
    ].map(([code, title, type, value, maxDiscount, minTotal, limit, used, active]) => ({
        id: id(), code, title, type, value, max_discount: maxDiscount, min_order_total: minTotal,
        usage_limit: limit, per_user_limit: 1, used_count: used, category: null, category_id: null,
        starts_at: DateTime.now().subDays(20), expires_at: DateTime.now().addDays(active ? 45 : -20),
        is_active: active, is_exhausted: limit !== null && used >= limit,
        created_at: DateTime.now().subDays(40),
    }));
}

function makeTicket(index, user, staff, orders) {
    const subjects = [
        ['پیگیری وضعیت ارسال سفارش', 'سلام، سه روز از ثبت سفارشم می‌گذرد و هنوز کد رهگیری برایم ارسال نشده است. ممکن است وضعیت را بررسی کنید؟'],
        ['درخواست مرجوعی کالا', 'کالای دریافتی با تصویر سایت تفاوت رنگ دارد. مایلم درخواست مرجوعی ثبت کنم.'],
        ['مشکل در پرداخت اینترنتی', 'مبلغ از حسابم کسر شد اما سفارش ثبت نشد. لطفاً بررسی بفرمایید.'],
        ['سؤال درباره گارانتی', 'گارانتی این محصول توسط کدام شرکت ارائه می‌شود و شامل چه مواردی است؟'],
    ];

    const [subject, body] = pick(subjects);
    const status = pick(['open', 'answered', 'answered', 'closed']);
    const created = DateTime.now().subDays(rand(0, 50));

    const ticket = {
        id: id(), code: `TK-${String(1001 + index).padStart(5, '0')}`,
        user, user_id: user.id,
        order: chance(60) ? pick(orders) : null,
        subject, department: pick(['support', 'orders', 'technical', 'finance']),
        department_label: 'پشتیبانی',
        priority: pick(['low', 'normal', 'normal', 'high']),
        status,
        created_at: created, updated_at: created.addHours(rand(1, 30)),
    };

    const messages = [{
        id: id(), ticket_id: ticket.id, user, body, is_staff: false, created_at: created,
    }];

    if (status !== 'open') {
        messages.push({
            id: id(), ticket_id: ticket.id, user: staff, is_staff: true,
            body: 'با سلام و احترام؛ درخواست شما ثبت شد و همکاران واحد مربوطه در حال بررسی هستند. نتیجه حداکثر تا ۲۴ ساعت آینده از همین طریق اطلاع‌رسانی می‌شود.',
            created_at: created.addHours(rand(2, 20)),
        });
    }

    ticket.messages = new Collection(messages);
    ticket.messages_count = messages.length;
    ticket.latestMessage = messages[messages.length - 1];

    return ticket;
}

function makeNotifications() {
    return [
        ['سفارش شما ارسال شد', 'سفارش DG14050412 به شرکت پست تحویل داده شد و کد رهگیری در صفحه سفارش قابل مشاهده است.', 'truck', false],
        ['دیدگاه شما منتشر شد', 'دیدگاه شما درباره «هدفون بی‌سیم» پس از بررسی کارشناسان منتشر شد.', 'chat', false],
        ['کاهش قیمت کالای مورد علاقه', 'قیمت یکی از کالاهای فهرست علاقه‌مندی‌های شما ۱۲٪ کاهش یافت.', 'percent', true],
        ['کد تخفیف تازه', 'کد DIGINO10 برای خرید بعدی شما فعال شد.', 'gift', true],
    ].map(([title, body, icon, read]) => ({
        id: `n-${id()}`, type: 'App\\Notifications\\Generic',
        data: { title, body, icon, url: null },
        read_at: read ? DateTime.now().subDays(rand(1, 5)) : null,
        created_at: DateTime.now().subDays(rand(0, 12)),
    }));
}

function makeSettings() {
    return {
        site_name: 'دیجی‌نو',
        site_tagline: 'خرید هوشمند کالای دیجیتال',
        site_description: 'دیجی‌نو فروشگاه اینترنتی کالای دیجیتال، لوازم خانگی، مد و پوشاک و کالای سوپرمارکتی با ضمانت اصالت و ارسال سریع به سراسر ایران است.',
        support_phone: '021-91008080',
        support_email: 'support@digino.example',
        address: 'تهران، خیابان ولیعصر، بالاتر از میدان ونک، برج دیجی‌نو، طبقه ۹',
        working_hours: 'شنبه تا چهارشنبه ۹ تا ۱۸ — پنجشنبه ۹ تا ۱۳',
        free_shipping_from: 5_000_000,
        shipping_cost: 49_000,
        low_stock_threshold: 5,
        max_cart_qty: 5,
        guest_checkout: false,
        auto_approve_reviews: false,
        instagram: 'https://instagram.com/digino',
        telegram: 'https://t.me/digino',
        linkedin: 'https://linkedin.com/company/digino',
        twitter: 'https://x.com/digino',
        maintenance_mode: false,
        maintenance_message: 'دیجی‌نو در حال به‌روزرسانی است. تا دقایقی دیگر باز می‌گردیم.',
    };
}

module.exports = {
    buildWorld,
    OrderStatus,
    PaymentStatus,
    ReviewStatus,
    UserRole,
    Paginator,
    rand,
    pick,
    chance,
};
