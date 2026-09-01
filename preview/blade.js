/**
 * ─────────────────────────────────────────────────────────────────────────────
 *  mini-Blade  —  a small Blade-compatible template engine for Node
 * ─────────────────────────────────────────────────────────────────────────────
 *  The DigiNo project is a Laravel application, but this sandbox has no PHP
 *  runtime, so the design still needs to be reviewable in a browser. This engine
 *  compiles the very same `resources/views/**.blade.php` files to JavaScript and
 *  renders them against fixture data that mirrors the database seeders.
 *
 *  Supported: @extends @section @yield @parent @hasSection @sectionMissing
 *             @include @includeIf @includeWhen @each @push @prepend @stack @once
 *             @if @elseif @else @unless @isset @empty @endisset
 *             @foreach @forelse @empty @endforelse @for @while @continue @break
 *             @switch @case @default @break @endswitch
 *             @auth @guest @admin @superadmin @can @cannot
 *             @csrf @method @json @class @style @checked @selected @disabled
 *             @error @php @endphp @dd @toman @fa @jalali @verbatim
 *             {{ }} {!! !!} {{-- --}} and <x-component /> tags with slots.
 * ─────────────────────────────────────────────────────────────────────────────
 */
'use strict';

const fs = require('fs');
const path = require('path');

class Blade {
    constructor({ viewPath, helpers = {} }) {
        this.viewPath = viewPath;
        this.helpers = helpers;
        this.cache = new Map();
    }

    /* ------------------------------------------------------------- public */

    render(view, data = {}) {
        const state = { sections: {}, stacks: {}, once: new Set() };
        const html = this.renderView(view, data, state);
        return html;
    }

    renderView(view, data, state) {
        const compiled = this.compiled(view);
        const scope = this.scope(data, state, view);
        let out;

        try {
            out = compiled.fn(scope);
        } catch (error) {
            error.message = `[${view}] ${error.message}`;
            throw error;
        }

        if (scope.__extends) {
            const parent = scope.__extends;
            scope.__extends = null;
            return this.renderView(parent, data, state);
        }

        return out;
    }

    compiled(view) {
        if (this.cache.has(view)) return this.cache.get(view);

        const file = this.resolve(view);
        const source = fs.readFileSync(file, 'utf8');
        const body = this.compile(source, view);
        let fn;

        try {
            // eslint-disable-next-line no-new-func
            fn = new Function('__scope', `with (__scope) { ${body} }`);
        } catch (error) {
            const numbered = body.split('\n').map((l, i) => `${i + 1}| ${l}`).join('\n');
            throw new Error(`Compile error in ${view}: ${error.message}\n${numbered.slice(0, 4000)}`);
        }

        const record = {
            fn: (scope) => {
                const proxy = new Proxy(scope, {
                    has: () => true,
                    get: (target, key) => (key === Symbol.unscopables ? undefined : target[key]),
                    set: (target, key, value) => { target[key] = value; return true; },
                });

                scope.__scope = proxy; // `@props` and reserved-word variables address the scope directly
                return fn(proxy);
            },
        };
        this.cache.set(view, record);
        return record;
    }

    resolve(view) {
        const rel = view.replace(/\./g, '/') + '.blade.php';
        const file = path.join(this.viewPath, rel);
        if (!fs.existsSync(file)) throw new Error(`View not found: ${view} (${rel})`);
        return file;
    }

    exists(view) {
        try {
            this.resolve(view);
            return true;
        } catch {
            return false;
        }
    }

    /* ------------------------------------------------------------- scope */

    scope(data, state, view) {
        const engine = this;
        const scope = Object.create(null);

        Object.assign(scope, this.helpers, data);

        scope.__out = '';
        scope.__extends = null;
        scope.__state = state;
        scope.__view = view;

        scope.__e = (value) => engine.escape(value);
        scope.__raw = (value) => (value === null || value === undefined || value === false ? '' : String(value));

        scope.__extend = (parent) => { scope.__extends = parent; };

        scope.__section = (name, value) => { if (state.sections[name] === undefined) state.sections[name] = value; };
        scope.__startSection = (name) => { state.__stack = state.__stack || []; state.__stack.push({ name, buffer: scope.__out }); scope.__out = ''; };
        scope.__endSection = (append = false) => {
            const frame = state.__stack.pop();
            const captured = scope.__out;
            scope.__out = frame.buffer;
            if (append && state.sections[frame.name] !== undefined) {
                state.sections[frame.name] = captured.replace('@parent', state.sections[frame.name]);
            } else if (state.sections[frame.name] === undefined) {
                state.sections[frame.name] = captured;
            }
        };
        scope.__yield = (name, fallback = '') => (state.sections[name] !== undefined && state.sections[name] !== '' ? state.sections[name] : fallback);
        scope.__hasSection = (name) => state.sections[name] !== undefined && String(state.sections[name]).trim() !== '';

        scope.__startPush = (name, prepend = false) => { state.__pushStack = state.__pushStack || []; state.__pushStack.push({ name, prepend, buffer: scope.__out }); scope.__out = ''; };
        scope.__endPush = () => {
            const frame = state.__pushStack.pop();
            const captured = scope.__out;
            scope.__out = frame.buffer;
            state.stacks[frame.name] = state.stacks[frame.name] || [];
            if (frame.prepend) state.stacks[frame.name].unshift(captured);
            else state.stacks[frame.name].push(captured);
        };
        scope.__stack = (name) => (state.stacks[name] || []).join('\n');

        scope.__once = (id) => {
            if (state.once.has(id)) return false;
            state.once.add(id);
            return true;
        };

        scope.__include = (name, extra = {}) => {
            if (!engine.exists(name)) return `<!-- missing view: ${name} -->`;
            const childData = Object.assign(Object.create(null), data, extra);
            const compiled = engine.compiled(name);
            const childScope = engine.scope(childData, state, name);
            return compiled.fn(childScope);
        };

        scope.__component = (name, attrs = {}, slot = '') => engine.renderComponent(name, attrs, slot, data, state);

        scope.__loop = (items) => engine.loopMeta(items);

        // @props(['product', 'size' => 'base']) — positional entries are names
        // without a default; keyed entries carry one.
        scope.__props = (target, defaults) => {
            const names = [];

            Object.entries(defaults || {}).forEach(([key, value]) => {
                const positional = /^__\d+$/.test(key);
                const name = positional ? String(value) : key;
                names.push(name);
                if (target[name] === undefined) target[name] = positional ? null : value;
            });

            if (target.attributes) target.attributes = target.attributes.except(names);
        };

        return scope;
    }

    loopMeta(items) {
        const list = this.helpers.__items(items);
        return list.map((value, index) => ({
            value,
            loop: {
                index,
                iteration: index + 1,
                remaining: list.length - index - 1,
                count: list.length,
                first: index === 0,
                last: index === list.length - 1,
                even: (index + 1) % 2 === 0,
                odd: (index + 1) % 2 === 1,
                depth: 1,
                parent: null,
            },
        }));
    }

    renderComponent(name, attrs, slot, data, state) {
        const candidates = [`components.${name}`, name];
        const view = candidates.find((c) => this.exists(c));
        if (!view) return `<!-- missing component: ${name} -->`;

        const bag = this.attributeBag(attrs);
        const childData = Object.assign(Object.create(null), data, attrs, {
            attributes: bag,
            slot: {
                __isHtml: true,
                toHtml: () => slot,
                toString: () => slot,
                isEmpty: () => !slot.trim(),
                isNotEmpty: () => !!slot.trim(),
            },
        });

        const compiled = this.compiled(view);
        const childScope = this.scope(childData, state, view);
        childScope.__attrs = attrs;

        try {
            return compiled.fn(childScope);
        } catch (error) {
            error.message = `<x-${name}> ${error.message}`;
            throw error;
        }
    }

    attributeBag(attrs) {
        const skip = new Set();
        const bag = {
            all: () => attrs,
            get: (key, fallback = null) => (attrs[key] !== undefined ? attrs[key] : fallback),
            has: (key) => attrs[key] !== undefined,
            merge: (defaults = {}) => {
                const merged = Object.assign({}, defaults, attrs);
                if (defaults.class && attrs.class) merged.class = `${defaults.class} ${attrs.class}`;
                return this.attributeBag(merged);
            },
            except: (keys) => {
                const list = Array.isArray(keys) ? keys : [keys];
                const copy = Object.assign({}, attrs);
                list.forEach((k) => delete copy[k]);
                return this.attributeBag(copy);
            },
            only: (keys) => {
                const list = Array.isArray(keys) ? keys : [keys];
                const copy = {};
                list.forEach((k) => { if (attrs[k] !== undefined) copy[k] = attrs[k]; });
                return this.attributeBag(copy);
            },
            class: (extra) => bag.merge({ class: typeof extra === 'string' ? extra : '' }),
            toHtml: () => Object.entries(attrs)
                .filter(([key, value]) => !skip.has(key) && value !== false && value !== null && value !== undefined)
                .map(([key, value]) => (value === true ? key : `${key}="${String(value).replace(/"/g, '&quot;')}"`))
                .join(' '),
        };
        bag.toString = bag.toHtml;
        return bag;
    }

    escape(value) {
        if (value === null || value === undefined || value === false) return '';
        if (typeof value === 'object' && (value.__isHtml || typeof value.toHtml === 'function')) return String(value.toHtml ? value.toHtml() : value);
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    /* ----------------------------------------------------------- compiler */

    compile(source, view) {
        let src = source;

        // {{-- comments --}}
        src = src.replace(/\{\{--[\s\S]*?--\}\}/g, '');

        // @verbatim blocks are protected from every other transformation.
        const verbatim = [];
        src = src.replace(/@verbatim([\s\S]*?)@endverbatim/g, (_, body) => {
            verbatim.push(body);
            return `\u0000VERBATIM${verbatim.length - 1}\u0000`;
        });

        src = this.compileComponents(src);

        const lines = [];
        const emit = (text) => { if (text) lines.push(`__out += ${JSON.stringify(text)};`); };
        const code = (js) => lines.push(js);

        const pattern = /\u0004([A-Za-z0-9+/=]+)\u0004|@(\w+)()|\{!!([\s\S]*?)!!\}|\{\{([\s\S]*?)\}\}/g;

        let cursor = 0;
        let match;
        let switchPreamble = 0;
        const forelseStack = [];

        while ((match = pattern.exec(src)) !== null) {
            if (!switchPreamble) emit(src.slice(cursor, match.index));
            cursor = match.index + match[0].length;

            if (match[1] !== undefined) { // pre-compiled component call
                code(`__out += __raw(${Buffer.from(match[1], 'base64').toString('utf8')});`);
                continue;
            }
            if (match[4] !== undefined) { // {!! raw !!}
                code(`__out += __raw(${this.expr(match[4])});`);
                continue;
            }
            if (match[5] !== undefined) { // {{ escaped }}
                code(`__out += __e(${this.expr(match[5])});`);
                continue;
            }

            const directive = match[2];
            let args = null;

            if (ARGUMENT_DIRECTIVES.has(directive)) {
                const paren = readArguments(src, cursor);
                if (paren) {
                    args = paren.args;
                    cursor = paren.end;
                    pattern.lastIndex = cursor;
                }
            }

            switch (directive) {
                /* ---------------------------------------------- inheritance */
                case 'extends': code(`__extend(${this.expr(args)});`); break;
                case 'section': {
                    const parts = this.splitArgs(args);
                    if (parts.length > 1) code(`__section(${this.expr(parts[0])}, ${this.expr(parts.slice(1).join(','))});`);
                    else code(`__startSection(${this.expr(parts[0])});`);
                    break;
                }
                case 'endsection':
                case 'stop': code('__endSection();'); break;
                case 'show': code('__endSection(); __out += __yield(__lastSection);'); break;
                case 'append': code('__endSection(true);'); break;
                case 'yield': {
                    const parts = this.splitArgs(args);
                    code(`__out += __yield(${this.expr(parts[0])}${parts[1] ? `, ${this.expr(parts[1])}` : ''});`);
                    break;
                }
                case 'hasSection': code(`if (__hasSection(${this.expr(args)})) {`); break;
                case 'sectionMissing': code(`if (!__hasSection(${this.expr(args)})) {`); break;
                case 'endif': code('}'); break;

                /* --------------------------------------------------- stacks */
                case 'push': code(`__startPush(${this.expr(args)});`); break;
                case 'endpush': code('__endPush();'); break;
                case 'prepend': code(`__startPush(${this.expr(args)}, true);`); break;
                case 'endprepend': code('__endPush();'); break;
                case 'stack': code(`__out += __stack(${this.expr(args)});`); break;
                case 'once': code(`if (__once(${JSON.stringify(view + ':' + match.index)})) {`); break;
                case 'endonce': code('}'); break;

                /* ------------------------------------------------- includes */
                case 'include': {
                    const parts = this.splitArgs(args);
                    code(`__out += __include(${this.expr(parts[0])}${parts[1] ? `, ${this.expr(parts.slice(1).join(','))}` : ''});`);
                    break;
                }
                case 'includeIf': {
                    const parts = this.splitArgs(args);
                    code(`__out += __include(${this.expr(parts[0])}${parts[1] ? `, ${this.expr(parts.slice(1).join(','))}` : ''});`);
                    break;
                }
                case 'includeWhen': {
                    const parts = this.splitArgs(args);
                    code(`if (${this.expr(parts[0])}) { __out += __include(${this.expr(parts[1])}${parts[2] ? `, ${this.expr(parts.slice(2).join(','))}` : ''}); }`);
                    break;
                }
                case 'each': {
                    const parts = this.splitArgs(args);
                    code(`__out += __items(${this.expr(parts[1])}).map((__it) => __include(${this.expr(parts[0])}, { [${this.expr(parts[2])}]: __it })).join('');`);
                    break;
                }

                /* ---------------------------------------------- conditionals */
                case 'if': code(`if (${this.expr(args)}) {`); break;
                case 'elseif': code(`} else if (${this.expr(args)}) {`); break;
                case 'else':
                    if (forelseStack.length && forelseStack[forelseStack.length - 1].inLoop) code('} else {');
                    else code('} else {');
                    break;
                case 'unless': code(`if (!(${this.expr(args)})) {`); break;
                case 'endunless': code('}'); break;
                case 'isset': code(`if ((${this.expr(args)}) !== undefined && (${this.expr(args)}) !== null) {`); break;
                case 'endisset': code('}'); break;
                case 'auth': code('if (__auth()) {'); break;
                case 'endauth': code('}'); break;
                case 'guest': code('if (!__auth()) {'); break;
                case 'endguest': code('}'); break;
                case 'admin': code('if (__isAdmin()) {'); break;
                case 'endadmin': code('}'); break;
                case 'superadmin': code('if (__isSuperAdmin()) {'); break;
                case 'endsuperadmin': code('}'); break;
                case 'can': code(`if (__can(${this.expr(args)})) {`); break;
                case 'endcan': code('}'); break;
                case 'cannot': code(`if (!__can(${this.expr(args)})) {`); break;
                case 'endcannot': code('}'); break;
                case 'error': code(`if (__error(${this.expr(args)})) { var message = __error(${this.expr(args)});`); break;
                case 'enderror': code('}'); break;

                /* --------------------------------------------------- loops */
                case 'foreach': {
                    const { list, item, key } = this.parseForeach(args);
                    const idx = `__i${lines.length}`;
                    code(`{ const __entries = __loop(${list}); for (let ${idx} = 0; ${idx} < __entries.length; ${idx}++) { const loop = __entries[${idx}].loop; ${key ? `const ${key} = __keys(${list})[${idx}];` : ''} const ${item} = __entries[${idx}].value;`);
                    break;
                }
                case 'endforeach': code('} }'); break;
                case 'forelse': {
                    const { list, item, key } = this.parseForeach(args);
                    const idx = `__i${lines.length}`;
                    forelseStack.push(true);
                    code(`{ const __entries = __loop(${list}); if (__entries.length) { for (let ${idx} = 0; ${idx} < __entries.length; ${idx}++) { const loop = __entries[${idx}].loop; ${key ? `const ${key} = __keys(${list})[${idx}];` : ''} const ${item} = __entries[${idx}].value;`);
                    break;
                }
                case 'empty':
                    if (args === null || args === '') {
                        code('} } else {');
                    } else if (forelseStack.length && args === null) {
                        code('} } else {');
                    } else {
                        code(`if (__empty(${this.expr(args)})) {`);
                    }
                    break;
                case 'endempty': code('}'); break;
                case 'endforelse': forelseStack.pop(); code('} }'); break;
                case 'for': code(`for (${this.expr(args).replace(/;\s*$/, '')}) {`); break;
                case 'endfor': code('}'); break;
                case 'while': code(`while (${this.expr(args)}) {`); break;
                case 'endwhile': code('}'); break;
                case 'continue': code(args ? `if (${this.expr(args)}) continue;` : 'continue;'); break;
                case 'break':
                    // inside @switch the bare @break ends a case
                    code(args ? `if (${this.expr(args)}) break;` : 'break;');
                    break;

                /* -------------------------------------------------- switch */
                case 'switch': switchPreamble += 1; code(`switch (${this.expr(args)}) {`); break;
                case 'case': switchPreamble = 0; code(`case ${this.expr(args)}:`); break;
                case 'default': switchPreamble = 0; code('default:'); break;
                case 'endswitch': switchPreamble = 0; code('}'); break;

                /* --------------------------------------------------- forms */
                case 'csrf': code(`__out += '<input type="hidden" name="_token" value="preview-csrf-token">';`); break;
                case 'method': code(`__out += '<input type="hidden" name="_method" value="' + ${this.expr(args)} + '">';`); break;
                case 'json': code(`__out += __raw(JSON.stringify(${this.expr(this.splitArgs(args)[0])}));`); break;
                case 'class': code(`__out += 'class="' + __classes(${this.expr(args)}) + '"';`); break;
                case 'style': code(`__out += 'style="' + __classes(${this.expr(args)}) + '"';`); break;
                case 'checked': code(`if (${this.expr(args)}) __out += 'checked';`); break;
                case 'selected': code(`if (${this.expr(args)}) __out += 'selected';`); break;
                case 'disabled': code(`if (${this.expr(args)}) __out += 'disabled';`); break;
                case 'readonly': code(`if (${this.expr(args)}) __out += 'readonly';`); break;
                case 'required': code(`if (${this.expr(args)}) __out += 'required';`); break;

                /* ----------------------------------------- custom directives */
                case 'toman': code(`__out += __e(toman(${this.expr(args)}));`); break;
                case 'fa': code(`__out += __e(fa_number(${this.expr(args)}));`); break;
                case 'jalali': code(`__out += __e(jalali(${this.expr(args)}));`); break;

                /* ------------------------------------------------- raw code */
                case 'php': {
                    if (args) {
                        code(this.statements(args));
                    } else {
                        const end = src.indexOf('@endphp', cursor);
                        const body = src.slice(cursor, end === -1 ? src.length : end);
                        cursor = end === -1 ? src.length : end + '@endphp'.length;
                        code(this.statements(body));
                    }
                    break;
                }
                case 'endphp': break;
                case 'props': code(`__props(__scope, ${this.expr(args)});`); break;
                case 'aware': break;
                case 'dd':
                case 'dump': break;

                default:
                    // Not a directive we know — emit verbatim (e.g. an @ in text/CSS).
                    emit(match[0]);
            }
        }

        if (!switchPreamble) emit(src.slice(cursor));

        // `__out` deliberately lives on the scope so @section/@push can swap buffers.
        let body = `__out = '';\n${lines.join('\n')}\nreturn __out;`;
        body = body.replace(/\u0000VERBATIM(\d+)\u0000/g, (_, i) => verbatim[Number(i)]);
        return body;
    }

    /* ------------------------------------------------- component tag pass */

    /**
     * Expand `<x-name …>` tags into `__component(...)` calls. A hand-rolled
     * scanner (rather than a regex) because attribute values legitimately
     * contain `>` — `:old="$product->price"` being the common case.
     */
    compileComponents(src) {
        let out = '';
        let i = 0;

        while (i < src.length) {
            const open = src.indexOf('<x-', i);
            if (open === -1) { out += src.slice(i); break; }

            const tag = readTag(src, open);
            if (!tag) { out += src.slice(i, open + 3); i = open + 3; continue; }

            out += src.slice(i, open);

            if (tag.selfClosing) {
                out += this.componentCall(tag.name, tag.attrs);
                i = tag.end;
                continue;
            }

            const close = findClosingTag(src, tag.name, tag.end);
            const slot = close === -1 ? '' : src.slice(tag.end, close);
            out += this.componentCall(tag.name, tag.attrs, this.compileComponents(slot));
            i = close === -1 ? tag.end : close + tag.name.length + 5;
        }

        return out;
    }

    componentCall(name, attrsRaw, slot = '') {
        const attrs = this.parseAttributes(attrsRaw || '');
        const slotJs = slot.trim()
            ? `(() => { ${this.compileFragment(slot)} })()`
            : "''";
        const js = `__component(${JSON.stringify(name)}, ${attrs}, ${slotJs})`;
        return `\u0004${Buffer.from(js, 'utf8').toString('base64')}\u0004`;
    }

    /** Compile a nested fragment (component slot) into a self-contained body. */
    compileFragment(source) {
        return this.compile(source, 'slot');
    }

    parseAttributes(raw) {
        const attrs = [];
        const re = /([:@]?[\w.-]+)(?:=("([^"]*)"|'([^']*)'))?/g;
        let m;

        while ((m = re.exec(raw)) !== null) {
            let key = m[1];
            const value = m[3] !== undefined ? m[3] : (m[4] !== undefined ? m[4] : null);

            if (value === null) {
                attrs.push(`${JSON.stringify(key)}: true`);
                continue;
            }

            if (key.startsWith(':')) {
                attrs.push(`${JSON.stringify(key.slice(1))}: (${this.expr(value)})`);
                continue;
            }

            if (/\{\{|\{!!/.test(value)) {
                const jsParts = [];
                let cursor = 0;
                const inner = /\{\{([\s\S]*?)\}\}|\{!!([\s\S]*?)!!\}/g;
                let im;
                while ((im = inner.exec(value)) !== null) {
                    if (im.index > cursor) jsParts.push(JSON.stringify(value.slice(cursor, im.index)));
                    jsParts.push(`__raw(${this.expr(im[1] !== undefined ? im[1] : im[2])})`);
                    cursor = im.index + im[0].length;
                }
                if (cursor < value.length) jsParts.push(JSON.stringify(value.slice(cursor)));
                attrs.push(`${JSON.stringify(key)}: (${jsParts.join(' + ')})`);
                continue;
            }

            attrs.push(`${JSON.stringify(key)}: ${JSON.stringify(value)}`);
        }

        return `{ ${attrs.join(', ')} }`;
    }

    /* -------------------------------------------------- expression helpers */

    splitArgs(args) {
        if (!args) return [];
        const parts = [];
        let depth = 0;
        let quote = null;
        let current = '';

        for (let i = 0; i < args.length; i++) {
            const c = args[i];

            if (quote) {
                current += c;
                if (c === quote && args[i - 1] !== '\\') quote = null;
                continue;
            }
            if (c === '"' || c === "'") { quote = c; current += c; continue; }
            if ('([{'.includes(c)) depth += 1;
            if (')]}'.includes(c)) depth -= 1;
            if (c === ',' && depth === 0) { parts.push(current.trim()); current = ''; continue; }
            current += c;
        }

        if (current.trim()) parts.push(current.trim());
        return parts;
    }

    parseForeach(args) {
        const m = args.match(/^(.*?)\s+as\s+(.*)$/s);
        if (!m) return { list: this.expr(args), item: '__item' };

        const list = this.expr(m[1]);
        const target = m[2].trim();
        const kv = target.match(/^(\$[\w]+)\s*=>\s*(\$[\w]+)$/);

        if (kv) return { list, item: kv[2].slice(1), key: kv[1].slice(1) };
        return { list, item: target.replace(/^\$/, '') };
    }

    /** Convert a PHP expression to its JavaScript equivalent. */
    expr(php) {
        if (php === null || php === undefined) return "''";
        let s = String(php).trim();
        if (!s) return "''";

        // Protect string literals.
        const strings = [];
        s = s.replace(/'(?:\\.|[^'\\])*'|"(?:\\.|[^"\\])*"/g, (lit) => {
            strings.push(lit);
            return `\u0002${strings.length - 1}\u0002`;
        });

        s = s.replace(/\\?(?:[A-Za-z_]\w*\\)+([A-Za-z_]\w*)/g, '$1'); // fully-qualified class names
        s = s.replace(/->/g, '\u0003');   // object access
        s = s.replace(/::/g, '\u0003');   // static access
        s = s.replace(/\$([A-Za-z_]\w*)/g, (_, name) => (RESERVED.has(name) ? `__scope[${JSON.stringify(name)}]` : name));
        s = s.replace(/\$/g, '');          // stray sigils
        s = s.replace(/\bfn\s*\(/g, '(');  // arrow functions: fn ($x) => …
        s = s.replace(/=>/g, '\u0006');   // arrows and array keys, disambiguated below
        s = s.replace(/\?:/g, '||');       // elvis
        s = s.replace(/\band\b/g, '&&').replace(/\bor\b/g, '||');
        s = s.replace(/\bnew\s+\w+/g, 'null');
        s = applyCasts(s);
        s = s.replace(/\bmatch\s*\(([^()]*)\)\s*\{([\s\S]*?)\}/g, (_, subject, body) => `__match(${subject}, {${body}})`);

        // Remaining "." characters are PHP string concatenation, unless they sit
        // between digits (a float literal).
        s = s.replace(/\./g, (dot, index) => {
            const before = s[index - 1];
            const after = s[index + 1];
            if (/\d/.test(before || '') && /\d/.test(after || '')) return '.';
            return '+';
        });

        s = s.replace(/\u0003/g, '.');
        s = arrowsAndKeys(s);
        s = arraysToObjects(s);
        s = wrapArrowObjects(s);
        s = s.replace(/\u0002(\d+)\u0002/g, (_, i) => interpolate(strings[Number(i)]));

        return s;
    }

    /** `foreach ($list as $key => $value) { … }` written inside a @php block. */
    phpForeach(chunk) {
        const open = chunk.indexOf('(');
        const close = chunk.lastIndexOf(')', chunk.indexOf('{') === -1 ? chunk.length : chunk.indexOf('{'));
        const head = chunk.slice(open + 1, close);
        const body = chunk.slice(chunk.indexOf('{', close) + 1, chunk.lastIndexOf('}'));
        const [subject, target] = head.split(/\bas\b/);
        const [keyVar, valueVar] = target.includes('=>') ? target.split('=>') : [null, target];
        const value = this.expr(valueVar.trim());
        const key = keyVar ? this.expr(keyVar.trim()) : '__ignored';

        return `for (const [${key}, ${value}] of Object.entries(__pairs(${this.expr(subject.trim())}))) {\n${this.statements(body)}\n}`;
    }

    /** `if (…) { … } elseif (…) { … } else { … }` written inside a @php block. */
    phpBlock(chunk) {
        const brace = chunk.indexOf('{');
        if (brace === -1) return `${this.expr(chunk)};`;

        const head = chunk.slice(0, brace);
        const body = chunk.slice(brace + 1, chunk.lastIndexOf('}'));
        const keyword = head.trim().startsWith('elseif') ? head.replace(/^\s*elseif/, 'else if') : head;

        return `${this.expr(keyword)} {\n${this.statements(body)}\n}`;
    }

    /** Convert a block of PHP statements (@php … @endphp) to JavaScript. */
    statements(php) {
        const text = String(php)
            .split('\n')
            .filter((line) => !/^\s*(\/\/|#)/.test(line))
            .join('\n');

        const chunks = [];
        let depth = 0;
        let quote = null;
        let current = '';

        for (let i = 0; i < text.length; i++) {
            const c = text[i];

            if (quote) {
                current += c;
                if (c === quote && text[i - 1] !== '\\') quote = null;
                continue;
            }
            if (c === '"' || c === "'") { quote = c; current += c; continue; }
            if ('([{'.includes(c)) depth += 1;
            if (')]}'.includes(c)) depth -= 1;

            if (c === ';' && depth === 0) { chunks.push(current); current = ''; continue; }

            // A block statement (foreach/if/…) ends at the brace that closes it.
            if (c === '}' && depth === 0) { chunks.push(`${current}}`); current = ''; continue; }

            current += c;
        }

        if (current.trim()) chunks.push(current);

        return chunks
            .map((chunk) => chunk.trim())
            .filter(Boolean)
            .map((chunk) => {
                const push = chunk.match(/^\$(\w+)\[\]\s*=\s*([\s\S]+)$/);
                if (push) return `${push[1]}.push(${this.expr(push[2])});`;
                if (/^\$[\w]+\s*=[^=]/.test(chunk)) return `var ${this.expr(chunk)};`;
                if (/^foreach\b/.test(chunk)) return this.phpForeach(chunk);
                if (/^(if|elseif|else|for|while)\b/.test(chunk)) return this.phpBlock(chunk);
                return `${this.expr(chunk)};`;
            })
            .join('\n');
    }
}

/** `x => {a: 1}` is a block in JS, so parenthesise object-returning arrows. */
function wrapArrowObjects(source) {
    let out = source;
    let index = 0;

    while ((index = out.indexOf('=>', index)) !== -1) {
        const after = out.slice(index + 2);
        const offset = after.length - after.trimStart().length;

        if (after.trimStart().startsWith('{')) {
            const braceAt = index + 2 + offset;
            const closeAt = matchingBrace(out, braceAt);
            if (closeAt !== -1) {
                out = `${out.slice(0, braceAt)}(${out.slice(braceAt, closeAt + 1)})${out.slice(closeAt + 1)}`;
                index = closeAt + 3;
                continue;
            }
        }

        index += 2;
    }

    return out;
}

function matchingBrace(source, start) {
    let depth = 0;

    for (let i = start; i < source.length; i++) {
        if (source[i] === '{') depth += 1;
        else if (source[i] === '}') {
            depth -= 1;
            if (depth === 0) return i;
        }
    }

    return -1;
}

/** `"code: {$order->code}"` → `` `code: ${order.code}` ``. */
function interpolate(literal) {
    if (!literal.startsWith('"') || !/\$/.test(literal)) return literal;

    const inner = literal.slice(1, -1)
        .replace(/`/g, '\\`')
        .replace(/\{\$([A-Za-z_][\w]*((->|\[)[^}]*)?)\}/g, (_, expr) => '${' + expr.replace(/->/g, '.') + '}')
        .replace(/\$([A-Za-z_][\w]*(?:->[A-Za-z_]\w*)*)/g, (_, expr) => '${' + expr.replace(/->/g, '.') + '}');

    return `\`${inner}\``;
}

/** Read `<x-name attr="…" …>` starting at `open`; returns null when malformed. */
function readTag(src, open) {
    const nameMatch = /^<x-([A-Za-z0-9._-]+)/.exec(src.slice(open));
    if (!nameMatch) return null;

    let i = open + nameMatch[0].length;
    let quote = null;

    for (; i < src.length; i++) {
        const c = src[i];
        if (quote) { if (c === quote) quote = null; continue; }
        if (c === '"' || c === "'") { quote = c; continue; }
        if (c === '>') {
            const selfClosing = src[i - 1] === '/';
            return {
                name: nameMatch[1],
                attrs: src.slice(open + nameMatch[0].length, selfClosing ? i - 1 : i),
                end: i + 1,
                selfClosing,
            };
        }
    }

    return null;
}

/** Find the matching `</x-name>` for a tag, honouring nesting of the same name. */
function findClosingTag(src, name, from) {
    const openTag = new RegExp(`<x-${name.replace(/[.*+?^${}()|[\\]\\\\]/g, '\\\\$&')}[\\s/>]`, 'g');
    const closeTag = `</x-${name}>`;
    let depth = 1;
    let i = from;

    while (i < src.length) {
        const next = src.indexOf(closeTag, i);
        if (next === -1) return -1;

        openTag.lastIndex = i;
        let opens = 0;
        let m;
        while ((m = openTag.exec(src)) !== null && m.index < next) opens += 1;

        depth += opens - 1;
        if (depth === 0) return next;
        i = next + closeTag.length;
    }

    return -1;
}

/** Directives that accept a parenthesised argument list. */
const ARGUMENT_DIRECTIVES = new Set([
    'extends', 'section', 'yield', 'hasSection', 'sectionMissing', 'push', 'prepend', 'stack',
    'include', 'includeIf', 'includeWhen', 'includeFirst', 'each', 'component', 'slot', 'props',
    'if', 'elseif', 'unless', 'isset', 'empty', 'can', 'cannot', 'canany', 'error', 'foreach',
    'forelse', 'for', 'while', 'switch', 'case', 'continue', 'break', 'class', 'style', 'checked',
    'selected', 'disabled', 'readonly', 'required', 'json', 'method', 'php', 'lang', 'choice',
    'dd', 'dump', 'inject', 'toman', 'fa', 'aware', 'use', 'env', 'production', 'vite',
]);

/** Read a balanced `( … )` argument list starting at (or after spaces from) `index`. */
function readArguments(src, index) {
    let i = index;
    while (i < src.length && /\s/.test(src[i])) i += 1;
    if (src[i] !== '(') return null;

    let depth = 0;
    let quote = null;

    for (let j = i; j < src.length; j++) {
        const c = src[j];

        if (quote) {
            if (c === quote && src[j - 1] !== '\\') quote = null;
            continue;
        }
        if (c === '"' || c === "'") { quote = c; continue; }
        if (c === '(') depth += 1;
        if (c === ')') {
            depth -= 1;
            if (depth === 0) return { args: src.slice(i + 1, j), end: j + 1 };
        }
    }

    return null;
}

/** JS reserved words that may legitimately be PHP variable names. */
const RESERVED = new Set([
    'class', 'default', 'new', 'delete', 'function', 'var', 'let', 'const', 'this', 'with',
    'in', 'of', 'do', 'case', 'switch', 'catch', 'finally', 'throw', 'try', 'typeof', 'void',
    'enum', 'export', 'import', 'super', 'instanceof', 'yield', 'static', 'public', 'private',
]);

/**
 * `\u0006` currently stands for every PHP `=>`. Inside a parameter list it is an
 * arrow function; everywhere else it is an array key separator.
 */
function arrowsAndKeys(source) {
    return source.replace(/\u0006/g, (match, index) => {
        const before = source.slice(0, index).trimEnd();
        return before.endsWith(')') ? '=>' : ':';
    });
}

/** Convert PHP array literals that carry keys (`[a: b]`) into JS objects. */
function arraysToObjects(source) {
    let out = source;
    let guard = 0;

    while (guard++ < 400) {
        const range = innermostKeyedArray(out);
        if (!range) break;

        const inner = out.slice(range.start + 1, range.end);
        const items = splitTopLevel(inner);
        let positional = 0;

        const body = items.map((item) => {
            const trimmed = item.trim();
            if (!trimmed) return null;
            if (!hasTopLevelColon(trimmed)) return `${JSON.stringify(`__${positional++}`)}: ${trimmed}`;
            return computedKey(trimmed);
        }).filter(Boolean).join(', ');

        out = `${out.slice(0, range.start)}{${body}}${out.slice(range.end + 1)}`;
    }

    return out;
}

/** Wrap non-literal array keys so they become computed JS object keys. */
function computedKey(item) {
    let depth = 0;
    let quote = null;
    let question = 0;

    for (let i = 0; i < item.length; i++) {
        const c = item[i];
        if (quote) { if (c === quote && item[i - 1] !== '\\') quote = null; continue; }
        if (c === '"' || c === "'") { quote = c; continue; }
        if ('([{'.includes(c)) depth += 1;
        if (')]}'.includes(c)) depth -= 1;
        if (c === '?' && depth === 0 && item[i + 1] !== '?') question += 1;
        if (c === ':' && depth === 0) {
            if (question > 0) { question -= 1; continue; }
            const key = item.slice(0, i).trim();
            const value = item.slice(i + 1);
            const literal = /^\u0002\d+\u0002$/.test(key) || /^(['"]).*\1$/.test(key) || /^-?\d+$/.test(key) || /^[A-Za-z_]\w*$/.test(key);
            return literal ? `${key}: ${value}` : `[${key}]: ${value}`;
        }
    }

    return item;
}

function innermostKeyedArray(source) {
    const stack = [];
    let best = null;

    for (let i = 0; i < source.length; i++) {
        const c = source[i];
        if (c === '[') stack.push(i);
        else if (c === ']') {
            const start = stack.pop();
            if (start === undefined) continue;
            const inner = source.slice(start + 1, i);
            if (splitTopLevel(inner).some((item) => hasTopLevelColon(item))) {
                const depth = stack.length;
                if (!best || depth > best.depth) best = { start, end: i, depth };
            }
        }
    }

    return best;
}

function splitTopLevel(text) {
    const parts = [];
    let depth = 0;
    let quote = null;
    let current = '';

    for (let i = 0; i < text.length; i++) {
        const c = text[i];
        if (quote) { current += c; if (c === quote && text[i - 1] !== '\\') quote = null; continue; }
        if (c === '"' || c === "'") { quote = c; current += c; continue; }
        if ('([{'.includes(c)) depth += 1;
        if (')]}'.includes(c)) depth -= 1;
        if (c === ',' && depth === 0) { parts.push(current); current = ''; continue; }
        current += c;
    }

    if (current.trim()) parts.push(current);
    return parts;
}

function hasTopLevelColon(text) {
    let depth = 0;
    let quote = null;
    let question = 0;

    for (let i = 0; i < text.length; i++) {
        const c = text[i];
        if (quote) { if (c === quote && text[i - 1] !== '\\') quote = null; continue; }
        if (c === '"' || c === "'") { quote = c; continue; }
        if ('([{'.includes(c)) depth += 1;
        if (')]}'.includes(c)) depth -= 1;
        if (c === '?' && depth === 0 && text[i + 1] !== '?' && text[i + 1] !== '.') question += 1;
        if (c === ':' && depth === 0) {
            if (question > 0) { question -= 1; continue; }
            return true;
        }
    }

    return false;
}

/** `(string) $x` → `String(x)`, `(int) $x` → `Number(x)`, … */
function applyCasts(source) {
    const map = { string: 'String', int: 'Number', integer: 'Number', float: 'Number', double: 'Number', bool: 'Boolean', boolean: 'Boolean' };
    const re = /\((string|int|integer|float|double|bool|boolean|array|object)\)\s*/;

    let out = source;
    let guard = 0;

    while (guard++ < 200) {
        const m = re.exec(out);
        if (!m) break;

        const fn = map[m[1]];
        const rest = out.slice(m.index + m[0].length);
        const term = grabTerm(rest);

        out = fn && term
            ? `${out.slice(0, m.index)}${fn}(${term})${rest.slice(term.length)}`
            : `${out.slice(0, m.index)}${rest}`;
    }

    return out;
}

/** Grab the smallest complete term at the start of `text`. */
function grabTerm(text) {
    let depth = 0;
    let i = 0;

    for (; i < text.length; i++) {
        const c = text[i];
        if ('([{'.includes(c)) depth += 1;
        else if (')]}'.includes(c)) { if (depth === 0) break; depth -= 1; }
        else if (depth === 0 && /[\s,;=<>!&|+*/?:]/.test(c)) break;
    }

    return text.slice(0, i);
}

module.exports = { Blade };
