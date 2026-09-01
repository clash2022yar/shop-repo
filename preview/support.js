/**
 * Runtime support for the mini-Blade preview renderer: Laravel-flavoured
 * helpers (collections, dates, route/asset URLs, currency formatting) so the
 * Blade views can run unmodified outside PHP.
 */
'use strict';

/* ───────────────────────────────── numbers & text ───────────────────────── */

const FA_DIGITS = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];

function faNumber(value) {
    if (value === null || value === undefined) return '';
    return String(value).replace(/\d/g, (d) => FA_DIGITS[Number(d)]);
}

function enNumber(value) {
    if (value === null || value === undefined) return '';
    return String(value).replace(/[۰-۹]/g, (d) => String(FA_DIGITS.indexOf(d)));
}

function numberFormat(value, decimals = 0) {
    const n = Number(value || 0);
    return n.toLocaleString('en-US', { minimumFractionDigits: decimals, maximumFractionDigits: decimals });
}

/** Rials (stored) → Toman (displayed), grouped and localised. */
/** Mirrors the PHP helper: format the amount, optionally with the unit label. */
function toman(value, withLabel = false) {
    const text = numberFormat(Math.round(Number(value || 0)));
    return withLabel ? `${text} تومان` : text;
}

/* ────────────────────────────────── dates ───────────────────────────────── */

const JALALI_MONTHS = ['فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور', 'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند'];

function gregorianToJalali(gy, gm, gd) {
    const g_d_m = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];
    let jy = gy <= 1600 ? 0 : 979;
    let gy2 = gy <= 1600 ? gy - 621 : gy - 1600;
    const gy3 = gm > 2 ? gy2 + 1 : gy2;

    let days = 365 * gy2 + Math.floor((gy3 + 3) / 4) - Math.floor((gy3 + 99) / 100)
        + Math.floor((gy3 + 399) / 400) - 80 + gd + g_d_m[gm - 1];

    jy += 33 * Math.floor(days / 12053);
    days %= 12053;
    jy += 4 * Math.floor(days / 1461);
    days %= 1461;

    if (days > 365) {
        jy += Math.floor((days - 1) / 365);
        days = (days - 1) % 365;
    }

    const jm = days < 186 ? 1 + Math.floor(days / 31) : 7 + Math.floor((days - 186) / 30);
    const jd = 1 + (days < 186 ? days % 31 : (days - 186) % 30);

    return [jy, jm, jd];
}

class DateTime {
    constructor(input) {
        this.date = input instanceof Date ? new Date(input.getTime()) : new Date(input || Date.now());
    }

    static createFromTimestamp(seconds) {
        return new DateTime(new Date(Number(seconds) * 1000));
    }

    static now() { return new DateTime(new Date()); }
    static parse(v) { return new DateTime(v); }

    copy() { return new DateTime(this.date); }
    addDays(n) { const d = this.copy(); d.date.setDate(d.date.getDate() + n); return d; }
    subDays(n) { return this.addDays(-n); }
    addHours(n) { const d = this.copy(); d.date.setHours(d.date.getHours() + n); return d; }
    subHours(n) { return this.addHours(-n); }
    addMonths(n) { const d = this.copy(); d.date.setMonth(d.date.getMonth() + n); return d; }
    subMonths(n) { return this.addMonths(-n); }
    startOfDay() { const d = this.copy(); d.date.setHours(0, 0, 0, 0); return d; }
    endOfDay() { const d = this.copy(); d.date.setHours(23, 59, 59, 999); return d; }

    isPast() { return this.date.getTime() < Date.now(); }
    isFuture() { return this.date.getTime() > Date.now(); }
    get timestamp() { return Math.floor(this.date.getTime() / 1000); }
    getTimestamp() { return this.timestamp; }

    diffInDays(other) {
        const target = other instanceof DateTime ? other.date : new Date(other || Date.now());
        return Math.round((target - this.date) / 86400000);
    }

    diffForHumans() {
        const seconds = Math.round((Date.now() - this.date.getTime()) / 1000);
        const abs = Math.abs(seconds);
        const suffix = seconds >= 0 ? 'پیش' : 'دیگر';
        if (abs < 60) return `لحظاتی ${suffix}`;
        if (abs < 3600) return `${faNumber(Math.round(abs / 60))} دقیقه ${suffix}`;
        if (abs < 86400) return `${faNumber(Math.round(abs / 3600))} ساعت ${suffix}`;
        if (abs < 2592000) return `${faNumber(Math.round(abs / 86400))} روز ${suffix}`;
        if (abs < 31536000) return `${faNumber(Math.round(abs / 2592000))} ماه ${suffix}`;
        return `${faNumber(Math.round(abs / 31536000))} سال ${suffix}`;
    }

    jalaliParts() {
        return gregorianToJalali(this.date.getFullYear(), this.date.getMonth() + 1, this.date.getDate());
    }

    format(pattern = 'Y-m-d') {
        const pad = (n) => String(n).padStart(2, '0');
        const map = {
            Y: this.date.getFullYear(),
            m: pad(this.date.getMonth() + 1),
            d: pad(this.date.getDate()),
            H: pad(this.date.getHours()),
            i: pad(this.date.getMinutes()),
            s: pad(this.date.getSeconds()),
        };
        return String(pattern).replace(/[YmdHis]/g, (c) => map[c]);
    }

    toDateString() { return this.format('Y-m-d'); }
    toIso8601String() { return this.date.toISOString(); }
    toString() { return this.format('Y-m-d H:i:s'); }
}

function jalali(value, withTime = false) {
    if (!value) return '';
    const dt = value instanceof DateTime ? value : new DateTime(value);
    const [jy, jm, jd] = dt.jalaliParts();
    const base = `${faNumber(jd)} ${JALALI_MONTHS[jm - 1]} ${faNumber(jy)}`;
    return withTime ? `${base} ساعت ${faNumber(dt.format('H:i'))}` : base;
}

function jalaliHuman(value) {
    if (!value) return '';
    const dt = value instanceof DateTime ? value : new DateTime(value);
    const diff = Math.abs(Date.now() - dt.date.getTime());
    return diff < 6 * 86400000 ? dt.diffForHumans() : jalali(dt);
}

/* ─────────────────────────────── collections ────────────────────────────── */

function valueOf(item, key) {
    if (item === null || item === undefined) return undefined;
    if (typeof key === 'function') return key(item);
    return String(key).split('.').reduce((carry, part) => (carry === null || carry === undefined ? carry : carry[part]), item);
}

class Collection {
    constructor(items = []) {
        this.items = Array.isArray(items)
            ? items.slice()
            : (items && typeof items === 'object' ? Object.values(items) : []);
        this.keysList = Array.isArray(items) ? null : (items && typeof items === 'object' ? Object.keys(items) : null);
    }

    get length() { return this.items.length; }
    [Symbol.iterator]() { return this.items[Symbol.iterator](); }

    all() { return this.keysList ? Object.fromEntries(this.keysList.map((k, i) => [k, this.items[i]])) : this.items; }
    toArray() { return this.items; }
    count() { return this.items.length; }
    isEmpty() { return this.items.length === 0; }
    isNotEmpty() { return this.items.length > 0; }
    first(fn = null) { return fn ? this.items.find((i) => fn(i)) ?? null : (this.items[0] ?? null); }
    last() { return this.items[this.items.length - 1] ?? null; }
    take(n) { return new Collection(n >= 0 ? this.items.slice(0, n) : this.items.slice(n)); }
    slice(a, b) { return new Collection(this.items.slice(a, b)); }
    skip(n) { return new Collection(this.items.slice(n)); }
    filter(fn = (i) => !!i) { return new Collection(this.items.filter((i) => fn(i))); }
    reject(fn) { return new Collection(this.items.filter((i) => !fn(i))); }
    map(fn) { return new Collection(this.items.map((i, idx) => fn(i, idx))); }
    flatMap(fn) { return new Collection(this.items.flatMap((i) => fn(i))); }
    each(fn) { this.items.forEach((i, idx) => fn(i, idx)); return this; }
    values() { return new Collection(this.items); }
    keys() { return new Collection(this.keysList || this.items.map((_, i) => i)); }
    contains(v) { return typeof v === 'function' ? this.items.some((i) => v(i)) : this.items.includes(v); }
    every(fn) { return this.items.every((i) => fn(i)); }
    any(fn) { return this.items.some((i) => fn(i)); }
    sum(key = null) { return this.items.reduce((c, i) => c + Number(key ? valueOf(i, key) : i) || 0, 0); }
    avg(key = null) { return this.items.length ? this.sum(key) / this.items.length : 0; }
    max(key = null) { return this.items.reduce((c, i) => Math.max(c, Number(key ? valueOf(i, key) : i) || 0), 0); }
    min(key = null) { return this.items.reduce((c, i) => Math.min(c, Number(key ? valueOf(i, key) : i) || 0), Infinity); }
    pluck(key) { return new Collection(this.items.map((i) => valueOf(i, key))); }
    where(key, value) { return new Collection(this.items.filter((i) => valueOf(i, key) === value)); }
    whereIn(key, values) { return new Collection(this.items.filter((i) => values.includes(valueOf(i, key)))); }
    whereNotNull(key) { return new Collection(this.items.filter((i) => valueOf(i, key) !== null && valueOf(i, key) !== undefined)); }
    whereNull(key) { return new Collection(this.items.filter((i) => valueOf(i, key) === null || valueOf(i, key) === undefined)); }
    firstWhere(key, value) { return this.items.find((i) => (value === undefined ? !!valueOf(i, key) : valueOf(i, key) === value)) ?? null; }
    sortBy(key) { return new Collection(this.items.slice().sort((a, b) => (valueOf(a, key) > valueOf(b, key) ? 1 : -1))); }
    sortByDesc(key) { return new Collection(this.items.slice().sort((a, b) => (valueOf(a, key) < valueOf(b, key) ? 1 : -1))); }
    sort() { return new Collection(this.items.slice().sort()); }
    reverse() { return new Collection(this.items.slice().reverse()); }
    unique(key = null) {
        const seen = new Set();
        return new Collection(this.items.filter((i) => {
            const v = key ? valueOf(i, key) : i;
            if (seen.has(v)) return false;
            seen.add(v);
            return true;
        }));
    }
    groupBy(key) {
        const groups = {};
        this.items.forEach((i) => {
            const k = valueOf(i, key);
            (groups[k] = groups[k] || []).push(i);
        });
        return new Collection(Object.fromEntries(Object.entries(groups).map(([k, v]) => [k, new Collection(v)])));
    }
    mapWithKeys(fn) {
        const out = {};
        this.items.forEach((i) => Object.assign(out, fn(i)));
        return new Collection(out);
    }
    merge(other) {
        const otherItems = other instanceof Collection ? other.all() : other;
        if (this.keysList || (otherItems && !Array.isArray(otherItems) && typeof otherItems === 'object')) {
            return new Collection(Object.assign({}, this.all(), otherItems));
        }
        return new Collection(this.items.concat(Array.isArray(otherItems) ? otherItems : Object.values(otherItems || {})));
    }
    push(item) { this.items.push(item); return this; }
    chunk(size) {
        const out = [];
        for (let i = 0; i < this.items.length; i += size) out.push(new Collection(this.items.slice(i, i + size)));
        return new Collection(out);
    }
    random(n = null) {
        const shuffled = this.items.slice().sort(() => Math.random() - 0.5);
        return n === null ? (shuffled[0] ?? null) : new Collection(shuffled.slice(0, n));
    }
    shuffle() { return new Collection(this.items.slice().sort(() => Math.random() - 0.5)); }
    implode(glue) { return this.items.join(glue); }
    join(glue, last = null) {
        if (!last || this.items.length < 2) return this.items.join(glue);
        return `${this.items.slice(0, -1).join(glue)}${last}${this.items[this.items.length - 1]}`;
    }
    flatten() { return new Collection(this.items.flat(Infinity)); }
    relationLoaded() { return true; }
    toString() { return this.items.join(''); }
}

function collect(items) {
    if (items instanceof Collection) return items;
    return new Collection(items || []);
}

/** Simple length-aware paginator mirroring Laravel's public API. */
class Paginator extends Collection {
    constructor(items, { total = null, perPage = 24, currentPage = 1, path = '#' } = {}) {
        super(items);
        this.totalCount = total === null ? this.items.length : total;
        this.perPageCount = perPage;
        this.page = currentPage;
        this.path = path;
    }

    total() { return this.totalCount; }
    perPage() { return this.perPageCount; }
    currentPage() { return this.page; }
    lastPage() { return Math.max(1, Math.ceil(this.totalCount / this.perPageCount)); }
    hasPages() { return this.lastPage() > 1; }
    hasMorePages() { return this.page < this.lastPage(); }
    onFirstPage() { return this.page <= 1; }
    firstItem() { return this.totalCount ? (this.page - 1) * this.perPageCount + 1 : 0; }
    lastItem() { return Math.min(this.totalCount, this.page * this.perPageCount); }
    nextPageUrl() { return this.hasMorePages() ? `${this.path}?page=${this.page + 1}` : null; }
    previousPageUrl() { return this.onFirstPage() ? null : `${this.path}?page=${this.page - 1}`; }
    url(page) { return `${this.path}?page=${page}`; }
    appends() { return this; }
    withQueryString() { return this; }
    onEachSide() { return this; }
    links() { return this.renderLinks ? this.renderLinks(this) : ''; }
    elements() {
        const pages = {};
        for (let i = 1; i <= this.lastPage(); i++) pages[i] = this.url(i);
        return [pages];
    }
}

/* ───────────────────────────────── misc helpers ─────────────────────────── */

function empty(value) {
    if (value === null || value === undefined || value === false || value === '' || value === 0 || value === '0') return true;
    if (value instanceof Collection) return value.isEmpty();
    if (Array.isArray(value)) return value.length === 0;
    if (typeof value === 'object') return Object.keys(value).length === 0;
    return false;
}

function classes(input) {
    if (typeof input === 'string') return input;
    const out = [];
    if (Array.isArray(input)) {
        input.forEach((v) => { if (typeof v === 'string') out.push(v); });
        return out.join(' ');
    }
    Object.entries(input || {}).forEach(([key, value]) => { if (value) out.push(key); });
    return out.join(' ');
}

module.exports = {
    Collection,
    Paginator,
    DateTime,
    collect,
    faNumber,
    enNumber,
    numberFormat,
    toman,
    jalali,
    jalaliHuman,
    empty,
    classes,
    valueOf,
    gregorianToJalali,
};
