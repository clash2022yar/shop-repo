/*!
 * Digino — storefront & admin behaviour
 * ---------------------------------------------------------------------------
 * Plain ES2020. No framework, no bundler, no dependencies.
 * Everything is namespaced under `window.dg`.
 *
 * © یارمحمدی — تمامی حقوق محفوظ است.
 */
(function () {
  'use strict';

  /* ===================================================================== */
  /* 0. small helpers                                                       */
  /* ===================================================================== */

  const $ = (sel, root = document) => root.querySelector(sel);
  const $$ = (sel, root = document) => Array.from(root.querySelectorAll(sel));

  const FA_DIGITS = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];

  /** 12345 -> "۱۲,۳۴۵" */
  function faNumber(value) {
    return String(value ?? '').replace(/\d/g, (d) => FA_DIGITS[+d]);
  }

  /** 1234567 -> "1,234,567" then to Persian digits */
  function money(value) {
    return faNumber(Number(value || 0).toLocaleString('en-US'));
  }

  /** "۱۲۳" -> "123" (so numeric inputs accept Persian typing) */
  function enNumber(value) {
    return String(value ?? '')
      .replace(/[۰-۹]/g, (d) => '۰۱۲۳۴۵۶۷۸۹'.indexOf(d))
      .replace(/[٠-٩]/g, (d) => '٠١٢٣٤٥٦٧٨٩'.indexOf(d));
  }

  function debounce(fn, wait = 250) {
    let t;
    return function (...args) {
      clearTimeout(t);
      t = setTimeout(() => fn.apply(this, args), wait);
    };
  }

  function throttle(fn, wait = 100) {
    let last = 0;
    return function (...args) {
      const now = Date.now();
      if (now - last >= wait) {
        last = now;
        fn.apply(this, args);
      }
    };
  }

  const csrf = () => $('meta[name="csrf-token"]')?.content || '';

  /* ===================================================================== */
  /* 1. HTTP layer                                                          */
  /* ===================================================================== */

  const progress = {
    el: null,
    depth: 0,
    start() {
      this.el = this.el || $('#dg-progress');
      this.depth++;
      if (this.el) this.el.style.transform = 'scaleX(0.7)';
    },
    done() {
      this.depth = Math.max(0, this.depth - 1);
      if (!this.el || this.depth > 0) return;
      this.el.style.transform = 'scaleX(1)';
      setTimeout(() => {
        this.el.style.transition = 'none';
        this.el.style.transform = 'scaleX(0)';
        requestAnimationFrame(() => {
          this.el.style.transition = '';
        });
      }, 250);
    },
  };

  /**
   * Thin fetch wrapper that speaks the Digino JSON envelope:
   *   { ok: bool, message: string, ...payload }
   * Throws an Error carrying `.payload` and `.errors` on failure.
   */
  async function request(url, { method = 'GET', body = null, quiet = false, headers = {} } = {}) {
    if (!quiet) progress.start();

    const init = {
      method,
      credentials: 'same-origin',
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        Accept: 'application/json',
        'X-CSRF-TOKEN': csrf(),
        ...headers,
      },
    };

    if (body instanceof FormData) {
      init.body = body;
    } else if (body !== null) {
      init.headers['Content-Type'] = 'application/json';
      init.body = JSON.stringify(body);
    }

    try {
      const res = await fetch(url, init);
      const text = await res.text();
      let data = {};

      try {
        data = text ? JSON.parse(text) : {};
      } catch {
        throw new Error('پاسخ دریافتی از سرور معتبر نبود.');
      }

      if (res.status === 419) {
        throw new Error('نشست شما منقضی شده است. لطفاً صفحه را تازه‌سازی کنید.');
      }

      if (!res.ok || data.ok === false) {
        const err = new Error(data.message || 'خطایی رخ داد. دوباره تلاش کنید.');
        err.status = res.status;
        err.errors = data.errors || {};
        err.payload = data;
        throw err;
      }

      return data;
    } finally {
      if (!quiet) progress.done();
    }
  }

  const http = {
    get: (url, opts) => request(url, { ...opts, method: 'GET' }),
    post: (url, body, opts) => request(url, { ...opts, method: 'POST', body }),
    put: (url, body, opts) => request(url, { ...opts, method: 'PUT', body }),
    patch: (url, body, opts) => request(url, { ...opts, method: 'PATCH', body }),
    delete: (url, body, opts) => request(url, { ...opts, method: 'DELETE', body }),
  };

  /* ===================================================================== */
  /* 2. Toasts                                                              */
  /* ===================================================================== */

  const TOAST_ICONS = {
    success:
      '<svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="8.5"/><path d="M8.4 12.3l2.4 2.4 4.8-5"/></svg>',
    error:
      '<svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="8.5"/><path d="M9.2 9.2l5.6 5.6M14.8 9.2l-5.6 5.6"/></svg>',
    warning:
      '<svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 4.5l8.5 15h-17l8.5-15z"/><path d="M12 10v4"/><circle cx="12" cy="16.6" r=".9" fill="currentColor" stroke="none"/></svg>',
    info:
      '<svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="8.5"/><path d="M12 11v5"/><circle cx="12" cy="8" r=".9" fill="currentColor" stroke="none"/></svg>',
  };

  const TOAST_TONES = {
    success: 'text-success-500',
    error: 'text-brand-500',
    warning: 'text-warning-500',
    info: 'text-info-500',
  };

  function toast(message, type = 'success', timeout = 4000) {
    if (!message) return;
    const host = $('#dg-toasts');
    if (!host) return;

    const el = document.createElement('div');
    el.className = 'toast animate-fade-up';
    el.setAttribute('role', type === 'error' ? 'alert' : 'status');
    el.innerHTML =
      `<span class="mt-0.5 shrink-0 ${TOAST_TONES[type] || TOAST_TONES.info}">${TOAST_ICONS[type] || TOAST_ICONS.info}</span>` +
      `<p class="flex-1 text-[0.8125rem] leading-6 text-ink-800"></p>` +
      `<button type="button" class="shrink-0 text-ink-400 transition-colors hover:text-ink-700" aria-label="بستن">` +
      `<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"/></svg></button>`;

    el.querySelector('p').textContent = message;

    const dismiss = () => {
      el.classList.remove('animate-fade-up');
      el.classList.add('animate-fade-out');
      setTimeout(() => el.remove(), 200);
    };

    el.querySelector('button').addEventListener('click', dismiss);
    host.appendChild(el);

    if (timeout) setTimeout(dismiss, timeout);
    return el;
  }

  /* ===================================================================== */
  /* 3. Modals                                                              */
  /* ===================================================================== */

  const modal = {
    open(el) {
      if (typeof el === 'string') el = $(`[data-modal="${el}"]`) || $(`#${el}`);
      if (!el) return;

      el.classList.remove('hidden');
      document.body.style.overflow = 'hidden';

      el.querySelector('[data-modal-backdrop]')?.classList.add('animate-fade-in');
      el.querySelector('[data-modal-box]')?.classList.add('animate-zoom-in');

      const focusable = el.querySelector(
        'input:not([type=hidden]):not([disabled]), textarea, select, button:not([data-modal-close])'
      );
      setTimeout(() => focusable?.focus(), 60);

      el.__onKey = (e) => {
        if (e.key === 'Escape') modal.close(el);
        if (e.key === 'Tab') trapFocus(e, el);
      };
      document.addEventListener('keydown', el.__onKey);
    },

    close(el) {
      if (typeof el === 'string') el = $(`[data-modal="${el}"]`) || $(`#${el}`);
      if (!el || el.classList.contains('hidden')) return;

      const box = el.querySelector('[data-modal-box]');
      box?.classList.remove('animate-zoom-in');
      box?.classList.add('animate-zoom-out');

      setTimeout(() => {
        el.classList.add('hidden');
        box?.classList.remove('animate-zoom-out');
        el.querySelector('[data-modal-backdrop]')?.classList.remove('animate-fade-in');
        if (!$$('[data-modal]:not(.hidden), #dg-modal:not(.hidden), #dg-confirm:not(.hidden)').length) {
          document.body.style.overflow = '';
        }
      }, 160);

      if (el.__onKey) document.removeEventListener('keydown', el.__onKey);
    },

    /** Open the generic modal with arbitrary HTML. */
    show(title, html) {
      const host = $('#dg-modal');
      if (!host) return;
      $('#dg-modal-title').textContent = title || '';
      $('#dg-modal-body').innerHTML = html || '';
      this.open(host);
      bindAll($('#dg-modal-body'));
    },

    /** Promise-based confirmation dialog. */
    confirm(message, { title = 'آیا مطمئن هستید؟', accept = 'تأیید', danger = true } = {}) {
      return new Promise((resolve) => {
        const host = $('#dg-confirm');
        if (!host) return resolve(window.confirm(message));

        host.querySelector('[data-confirm-title]').textContent = title;
        host.querySelector('[data-confirm-message]').textContent = message || '';

        const acceptBtn = host.querySelector('[data-confirm-accept]');
        const cancelBtn = host.querySelector('[data-confirm-cancel]');
        acceptBtn.textContent = accept;
        acceptBtn.className = danger ? 'btn-primary flex-1' : 'btn-dark flex-1';

        const cleanup = (result) => {
          acceptBtn.replaceWith(acceptBtn.cloneNode(true));
          cancelBtn.replaceWith(cancelBtn.cloneNode(true));
          modal.close(host);
          resolve(result);
        };

        acceptBtn.addEventListener('click', () => cleanup(true), { once: true });
        cancelBtn.addEventListener('click', () => cleanup(false), { once: true });
        host.querySelector('[data-modal-backdrop]').addEventListener('click', () => cleanup(false), { once: true });

        modal.open(host);
      });
    },
  };

  function trapFocus(e, container) {
    const items = $$(
      'a[href], button:not([disabled]), textarea, input:not([type=hidden]), select, [tabindex]:not([tabindex="-1"])',
      container
    ).filter((el) => el.offsetParent !== null);

    if (!items.length) return;

    const first = items[0];
    const last = items[items.length - 1];

    if (e.shiftKey && document.activeElement === first) {
      e.preventDefault();
      last.focus();
    } else if (!e.shiftKey && document.activeElement === last) {
      e.preventDefault();
      first.focus();
    }
  }

  document.addEventListener('click', (e) => {
    const opener = e.target.closest('[data-modal-open]');
    if (opener) {
      e.preventDefault();
      modal.open(opener.dataset.modalOpen);
      return;
    }

    if (e.target.closest('[data-modal-close]') || e.target.matches('[data-modal-backdrop]')) {
      const host = e.target.closest('[data-modal], #dg-modal, #dg-confirm');
      if (host) modal.close(host);
    }
  });

  /* ===================================================================== */
  /* 4. Cart                                                                */
  /* ===================================================================== */

  const cart = {
    /** Paint every element that reflects cart state. */
    sync(summary) {
      if (!summary) return;

      $$('[data-cart-count]').forEach((el) => {
        el.textContent = faNumber(summary.count);
        el.classList.toggle('scale-0', !summary.count);
        if (summary.count) {
          el.classList.remove('animate-bounce-in');
          void el.offsetWidth;
          el.classList.add('animate-bounce-in');
        }
      });

      const map = {
        '[data-sum-subtotal]': summary.formatted.subtotal,
        '[data-sum-discount]': summary.formatted.product_discount,
        '[data-sum-coupon]': summary.formatted.coupon_discount,
        '[data-sum-shipping]': summary.formatted.shipping_cost,
        '[data-sum-total]': summary.formatted.grand_total,
      };

      Object.entries(map).forEach(([sel, value]) => {
        $$(sel).forEach((el) => {
          el.textContent = faNumber(value);
          el.classList.remove('animate-count-flip');
          void el.offsetWidth;
          el.classList.add('animate-count-flip');
        });
      });

      $$('[data-sum-items]').forEach((el) => (el.textContent = faNumber(summary.selected_count)));
      $$('[data-sum-lines]').forEach((el) => (el.textContent = faNumber(summary.lines)));

      $$('[data-coupon-row]').forEach((el) => el.classList.toggle('hidden', !summary.coupon_discount));
      $$('[data-coupon-code]').forEach((el) => (el.textContent = summary.coupon_code || ''));
      $$('[data-coupon-applied]').forEach((el) => el.classList.toggle('hidden', !summary.coupon_code));
      $$('[data-coupon-form]').forEach((el) => el.classList.toggle('hidden', !!summary.coupon_code));

      $$('[data-checkout-btn]').forEach((el) => {
        const disabled = summary.selected_count < 1;
        el.classList.toggle('pointer-events-none', disabled);
        el.classList.toggle('opacity-50', disabled);
      });

      // free-shipping nudge
      $$('[data-free-shipping]').forEach((el) => {
        if (summary.free_shipping) {
          el.innerHTML =
            '<span class="text-success-600 font-bold">سفارش شما شامل ارسال رایگان است.</span>';
        } else if (summary.free_shipping_remaining > 0) {
          el.innerHTML =
            'تنها <span class="font-bold text-brand-500">' +
            money(summary.free_shipping_remaining) +
            ' تومان</span> تا ارسال رایگان باقی مانده است.';
        } else {
          el.innerHTML = '';
        }
      });

      // Empty cart -> reload so the empty state renders server-side.
      if (summary.lines === 0 && $('[data-cart-page]')) {
        setTimeout(() => window.location.reload(), 400);
      }

      document.dispatchEvent(new CustomEvent('dg:cart-updated', { detail: summary }));
    },

    async add(productId, quantity = 1, variantId = null, trigger = null) {
      trigger?.setAttribute('disabled', 'disabled');
      trigger?.classList.add('opacity-60');

      try {
        const data = await http.post('/ajax/cart/add', {
          product_id: productId,
          variant_id: variantId,
          quantity,
        });

        this.sync(data.summary);
        toast(data.message, 'success');
        this.flyToCart(trigger);
      } catch (err) {
        toast(err.message, 'error');
      } finally {
        trigger?.removeAttribute('disabled');
        trigger?.classList.remove('opacity-60');
      }
    },

    async update(itemId, quantity) {
      try {
        const data = await http.patch(`/ajax/cart/items/${itemId}`, { quantity });

        if (data.removed) {
          this.removeLine(itemId);
        } else {
          const qtyEl = $(`[data-qty-value="${itemId}"]`);
          if (qtyEl) qtyEl.textContent = faNumber(data.quantity);
          const totalEl = $(`[data-line-total="${itemId}"]`);
          if (totalEl) {
            totalEl.firstChild.textContent = faNumber(data.line_total) + ' ';
            totalEl.classList.remove('animate-count-flip');
            void totalEl.offsetWidth;
            totalEl.classList.add('animate-count-flip');
          }
        }

        this.sync(data.summary);
      } catch (err) {
        toast(err.message, 'error');
      }
    },

    async remove(itemId) {
      try {
        const data = await http.delete(`/ajax/cart/items/${itemId}`);
        this.removeLine(itemId);
        this.sync(data.summary);
        toast(data.message, 'success');
      } catch (err) {
        toast(err.message, 'error');
      }
    },

    removeLine(itemId) {
      const line = $(`[data-cart-line="${itemId}"]`);
      if (!line) return;
      line.style.transition = 'all .3s cubic-bezier(.22,.61,.36,1)';
      line.style.opacity = '0';
      line.style.transform = 'translateX(-24px)';
      line.style.maxHeight = line.offsetHeight + 'px';
      setTimeout(() => {
        line.style.maxHeight = '0';
        line.style.padding = '0';
      }, 120);
      setTimeout(() => line.remove(), 400);
    },

    async select(itemId, selected) {
      try {
        const data = await http.post(`/ajax/cart/items/${itemId}/select`, { selected });
        this.sync(data.summary);
      } catch (err) {
        toast(err.message, 'error');
      }
    },

    async selectAll(selected) {
      try {
        const data = await http.post('/ajax/cart/select-all', { selected });
        $$('[data-cart-select]').forEach((cb) => (cb.checked = selected));
        this.sync(data.summary);
      } catch (err) {
        toast(err.message, 'error');
      }
    },

    async removeSelected() {
      const ok = await modal.confirm('کالاهای انتخاب‌شده از سبد خرید حذف می‌شوند.', {
        title: 'حذف موارد انتخاب‌شده',
        accept: 'حذف کن',
      });
      if (!ok) return;

      try {
        const data = await http.delete('/ajax/cart/selected');
        $$('[data-cart-select]:checked').forEach((cb) => this.removeLine(cb.dataset.cartSelect));
        this.sync(data.summary);
        toast(data.message, 'success');
      } catch (err) {
        toast(err.message, 'error');
      }
    },

    async applyCoupon(code, form) {
      try {
        const data = await http.post('/ajax/cart/coupon', { code });
        this.sync(data.summary);
        toast(data.message, 'success');
        form?.reset();
      } catch (err) {
        toast(err.message, 'error');
      }
    },

    async removeCoupon() {
      try {
        const data = await http.delete('/ajax/cart/coupon');
        this.sync(data.summary);
        toast(data.message, 'info');
      } catch (err) {
        toast(err.message, 'error');
      }
    },

    /** Little dot animation from the button to the cart icon. */
    flyToCart(trigger) {
      const target = $('[data-mini-cart-trigger]');
      if (!trigger || !target || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

      const from = trigger.getBoundingClientRect();
      const to = target.getBoundingClientRect();

      const dot = document.createElement('span');
      dot.className = 'pointer-events-none fixed z-[130] h-3 w-3 rounded-full bg-brand-500';
      dot.style.cssText += `left:${from.left + from.width / 2}px;top:${from.top}px;transition:transform .7s cubic-bezier(.4,0,.2,1),opacity .7s`;
      document.body.appendChild(dot);

      requestAnimationFrame(() => {
        dot.style.transform = `translate(${to.left + to.width / 2 - (from.left + from.width / 2)}px, ${to.top - from.top}px) scale(.3)`;
        dot.style.opacity = '0';
      });

      setTimeout(() => dot.remove(), 750);
    },

    async loadMini() {
      const panel = $('[data-mini-cart-panel]');
      if (!panel) return;
      try {
        const data = await http.get('/ajax/cart/mini', { quiet: true });
        panel.innerHTML = data.html;
        bindAll(panel);
      } catch {
        panel.innerHTML = '<p class="p-6 text-center text-sm text-ink-500">خطا در بارگذاری سبد خرید.</p>';
      }
    },
  };

  /* ===================================================================== */
  /* 5. Wishlist                                                            */
  /* ===================================================================== */

  async function toggleWishlist(productId, button) {
    try {
      const data = await http.post(`/ajax/wishlist/${productId}`);
      $$(`[data-wishlist-toggle="${productId}"]`).forEach((el) => {
        el.dataset.active = data.active ? '1' : '0';
        el.classList.remove('animate-heart-pop');
        void el.offsetWidth;
        el.classList.add('animate-heart-pop');
      });
      $$('[data-wishlist-count]').forEach((el) => (el.textContent = faNumber(data.count)));
      toast(data.message, data.active ? 'success' : 'info');
    } catch (err) {
      if (err.status === 401 || err.status === 403) {
        toast('برای افزودن به علاقه‌مندی‌ها ابتدا وارد حساب کاربری شوید.', 'warning');
        setTimeout(() => (window.location.href = '/login'), 1200);
        return;
      }
      toast(err.message, 'error');
    }
  }

  /* ===================================================================== */
  /* 6. AJAX forms                                                          */
  /* ===================================================================== */

  function clearFormErrors(form) {
    $$('[data-error-for]', form).forEach((el) => {
      el.textContent = '';
      el.classList.add('hidden');
    });
    $$('.field-error', form).forEach((el) => el.classList.remove('field-error'));
  }

  function showFormErrors(form, errors) {
    Object.entries(errors || {}).forEach(([field, messages]) => {
      const key = field.replace(/\.\d+$/, '');
      const holder = form.querySelector(`[data-error-for="${key}"]`);
      const input = form.querySelector(`[name="${key}"], [name="${key}[]"]`);

      if (holder) {
        holder.textContent = Array.isArray(messages) ? messages[0] : messages;
        holder.classList.remove('hidden');
        holder.classList.add('error-text');
      }
      input?.classList.add('field-error');
    });

    const firstInvalid = form.querySelector('.field-error');
    firstInvalid?.scrollIntoView({ behavior: 'smooth', block: 'center' });
    firstInvalid?.focus({ preventScroll: true });
  }

  function setSubmitting(form, busy) {
    const btn = form.querySelector('[type="submit"]');
    if (!btn) return;
    btn.disabled = busy;
    btn.classList.toggle('opacity-70', busy);
    btn.querySelector('[data-submit-spinner]')?.classList.toggle('hidden', !busy);
    const text = btn.querySelector('[data-submit-text]');
    if (text) text.classList.toggle('opacity-60', busy);
  }

  async function submitAjaxForm(form) {
    clearFormErrors(form);
    setSubmitting(form, true);

    const method = (form.dataset.method || form.method || 'POST').toUpperCase();
    const url = form.action;
    const fd = new FormData(form);

    // Normalise Persian digits typed into numeric fields.
    $$('[data-numeric]', form).forEach((input) => {
      fd.set(input.name, enNumber(input.value).replace(/[^\d.-]/g, ''));
    });

    if (method !== 'POST' && method !== 'GET') fd.append('_method', method);

    try {
      const data = await request(url, { method: method === 'GET' ? 'GET' : 'POST', body: fd });

      if (data.message) toast(data.message, 'success');
      if (form.hasAttribute('data-reset')) form.reset();

      form.dispatchEvent(new CustomEvent('dg:submitted', { detail: data, bubbles: true }));

      if (data.summary) cart.sync(data.summary);

      if (data.redirect && form.hasAttribute('data-redirect')) {
        setTimeout(() => (window.location.href = data.redirect), 600);
      } else if (data.redirect && !form.hasAttribute('data-no-redirect')) {
        setTimeout(() => (window.location.href = data.redirect), 600);
      } else if (form.hasAttribute('data-reload')) {
        setTimeout(() => window.location.reload(), 700);
      }

      const closeModal = form.closest('[data-modal], #dg-modal');
      if (closeModal && form.hasAttribute('data-close-modal')) modal.close(closeModal);

      return data;
    } catch (err) {
      showFormErrors(form, err.errors);
      toast(err.message, 'error');
      form.dispatchEvent(new CustomEvent('dg:failed', { detail: err, bubbles: true }));
      return null;
    } finally {
      setSubmitting(form, false);
    }
  }

  /* ===================================================================== */
  /* 7. Search autocomplete                                                 */
  /* ===================================================================== */

  function initSearch(root) {
    const input = $('[data-search-input]', root);
    const panel = $('[data-search-panel]', root);
    const results = $('[data-search-results]', root);
    const clearBtn = $('[data-search-clear]', root);
    if (!input || !panel) return;

    let activeIndex = -1;

    const hide = () => {
      panel.classList.add('hidden');
      activeIndex = -1;
    };

    const render = (data) => {
      const parts = [];

      if (data.products?.length) {
        parts.push('<p class="px-4 pb-1 pt-3 text-2xs font-bold text-ink-400">کالاها</p>');
        data.products.forEach((p) => {
          parts.push(
            `<a href="${p.url}" data-suggest-item class="flex items-center gap-3 px-4 py-2.5 transition-colors hover:bg-ink-50 data-[active]:bg-ink-50">
               <img src="${p.image}" alt="" class="h-11 w-11 shrink-0 rounded object-contain" loading="lazy">
               <span class="min-w-0 flex-1">
                 <span class="block truncate text-2xs text-ink-800">${escapeHtml(p.name)}</span>
                 ${p.brand ? `<span class="block text-[10px] text-ink-400">${escapeHtml(p.brand)}</span>` : ''}
               </span>
               <span class="shrink-0 text-2xs font-bold text-ink-700">${faNumber(p.price)}<span class="text-[10px] font-normal text-ink-500"> تومان</span></span>
             </a>`
          );
        });
      }

      if (data.categories?.length) {
        parts.push('<p class="border-t border-ink-100 px-4 pb-1 pt-3 text-2xs font-bold text-ink-400">دسته‌بندی‌ها</p>');
        data.categories.forEach((c) => {
          parts.push(
            `<a href="${c.url}" data-suggest-item class="flex items-center gap-2.5 px-4 py-2 text-2xs text-ink-700 transition-colors hover:bg-ink-50 data-[active]:bg-ink-50">
               <svg viewBox="0 0 24 24" class="h-4 w-4 text-ink-400" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="3.5" y="3.5" width="7.5" height="7.5" rx="1.4"/><rect x="13" y="3.5" width="7.5" height="7.5" rx="1.4"/><rect x="3.5" y="13" width="7.5" height="7.5" rx="1.4"/><rect x="13" y="13" width="7.5" height="7.5" rx="1.4"/></svg>
               ${escapeHtml(c.name)}
             </a>`
          );
        });
      }

      if (data.brands?.length) {
        parts.push('<p class="border-t border-ink-100 px-4 pb-1 pt-3 text-2xs font-bold text-ink-400">برندها</p>');
        data.brands.forEach((b) => {
          parts.push(
            `<a href="${b.url}" data-suggest-item class="flex items-center gap-2.5 px-4 py-2 text-2xs text-ink-700 transition-colors hover:bg-ink-50 data-[active]:bg-ink-50">
               <svg viewBox="0 0 24 24" class="h-4 w-4 text-ink-400" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"><path d="M3.5 11.6V4.5a1 1 0 011-1h7.1a1 1 0 01.7.3l8 8a1 1 0 010 1.4l-7.1 7.1a1 1 0 01-1.4 0l-8-8a1 1 0 01-.3-.7z"/></svg>
               ${escapeHtml(b.name)}
             </a>`
          );
        });
      }

      if (!parts.length) {
        results.innerHTML =
          '<div class="px-4 py-8 text-center text-2xs text-ink-500">نتیجه‌ای برای این عبارت پیدا نشد.</div>';
      } else {
        parts.push(
          `<a href="/search?q=${encodeURIComponent(input.value)}" class="block border-t border-ink-100 bg-ink-50 px-4 py-3 text-center text-2xs font-bold text-brand-500 transition-colors hover:bg-ink-100">
             مشاهده همه نتایج برای «${escapeHtml(input.value)}»
           </a>`
        );
        results.innerHTML = parts.join('');
      }

      panel.classList.remove('hidden');
      panel.classList.add('animate-fade-down');
    };

    const search = debounce(async () => {
      const term = input.value.trim();
      clearBtn?.classList.toggle('hidden', !term);
      clearBtn?.classList.toggle('grid', !!term);

      if (term.length < 2) return hide();

      results.innerHTML =
        '<div class="space-y-2 p-4">' +
        '<div class="skeleton h-12 w-full"></div><div class="skeleton h-12 w-full"></div><div class="skeleton h-12 w-full"></div></div>';
      panel.classList.remove('hidden');

      try {
        const data = await http.get(`/ajax/catalog/suggest?q=${encodeURIComponent(term)}`, { quiet: true });
        render(data);
      } catch {
        hide();
      }
    }, 300);

    input.addEventListener('input', search);
    input.addEventListener('focus', () => {
      if (input.value.trim().length >= 2) search();
    });

    clearBtn?.addEventListener('click', () => {
      input.value = '';
      input.focus();
      hide();
      clearBtn.classList.add('hidden');
    });

    input.addEventListener('keydown', (e) => {
      const items = $$('[data-suggest-item]', results);
      if (!items.length) return;

      if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
        e.preventDefault();
        items[activeIndex]?.removeAttribute('data-active');
        activeIndex = e.key === 'ArrowDown'
          ? (activeIndex + 1) % items.length
          : (activeIndex - 1 + items.length) % items.length;
        items[activeIndex].setAttribute('data-active', '');
        items[activeIndex].scrollIntoView({ block: 'nearest' });
      } else if (e.key === 'Enter' && activeIndex >= 0) {
        e.preventDefault();
        items[activeIndex].click();
      } else if (e.key === 'Escape') {
        hide();
      }
    });

    document.addEventListener('click', (e) => {
      if (!root.contains(e.target)) hide();
    });
  }

  function escapeHtml(str) {
    const d = document.createElement('div');
    d.textContent = str ?? '';
    return d.innerHTML;
  }

  /* ===================================================================== */
  /* 8. Dropdowns, tabs, rails, mega-menu, reveal                           */
  /* ===================================================================== */

  function initDropdown(root) {
    const trigger = $('[data-dropdown-trigger]', root);
    const panel = $('[data-dropdown-panel]', root);
    if (!trigger || !panel || root.__ddBound) return;
    root.__ddBound = true;

    let openTimer;

    const open = () => {
      clearTimeout(openTimer);
      $$('[data-dropdown] [data-dropdown-panel]').forEach((p) => {
        if (p !== panel) {
          p.classList.add('hidden');
          p.closest('[data-dropdown]')?.querySelector('[data-dropdown-trigger]')?.removeAttribute('data-open');
        }
      });
      panel.classList.remove('hidden');
      panel.classList.add('animate-fade-down');
      trigger.setAttribute('data-open', '');
      trigger.setAttribute('aria-expanded', 'true');
      root.dispatchEvent(new CustomEvent('dg:dropdown-open'));
    };

    const close = () => {
      panel.classList.add('hidden');
      trigger.removeAttribute('data-open');
      trigger.setAttribute('aria-expanded', 'false');
    };

    trigger.setAttribute('aria-expanded', 'false');
    trigger.addEventListener('click', (e) => {
      e.stopPropagation();
      panel.classList.contains('hidden') ? open() : close();
    });

    root.addEventListener('mouseleave', () => {
      openTimer = setTimeout(close, 250);
    });
    root.addEventListener('mouseenter', () => clearTimeout(openTimer));

    document.addEventListener('click', (e) => {
      if (!root.contains(e.target)) close();
    });
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') close();
    });
  }

  function initTabs(root) {
    const tabs = $$('[role="tab"]', root);
    if (!tabs.length) return;

    tabs.forEach((tab) => {
      tab.addEventListener('click', async (e) => {
        e.preventDefault();

        tabs.forEach((t) => {
          t.setAttribute('aria-selected', 'false');
          const p = document.getElementById(t.getAttribute('aria-controls'));
          p?.classList.add('hidden');
        });

        tab.setAttribute('aria-selected', 'true');
        const panel = document.getElementById(tab.getAttribute('aria-controls'));
        if (!panel) return;

        panel.classList.remove('hidden');
        panel.classList.add('animate-fade-in');

        // Lazily fetch tab content the first time it is opened.
        if (tab.dataset.tabUrl && !panel.dataset.loaded) {
          panel.innerHTML =
            '<div class="space-y-3 py-4"><div class="skeleton h-5 w-1/3"></div><div class="skeleton h-4 w-full"></div><div class="skeleton h-4 w-5/6"></div><div class="skeleton h-4 w-2/3"></div></div>';
          try {
            const data = await http.get(tab.dataset.tabUrl, { quiet: true });
            panel.innerHTML = data.html;
            panel.dataset.loaded = '1';
            bindAll(panel);
          } catch (err) {
            panel.innerHTML = `<p class="py-6 text-center text-sm text-ink-500">${escapeHtml(err.message)}</p>`;
          }
        }

        if (tab.dataset.tabHash) {
          history.replaceState(null, '', '#' + tab.dataset.tabHash);
        }
      });
    });
  }

  function initRail(root) {
    const rail = $('[data-rail]', root);
    const prev = $('[data-rail-prev]', root);
    const next = $('[data-rail-next]', root);
    if (!rail || root.__railBound) return;
    root.__railBound = true;

    const step = () => Math.max(240, rail.clientWidth * 0.8);

    const update = () => {
      const max = rail.scrollWidth - rail.clientWidth - 2;
      // RTL: scrollLeft is negative or zero in most engines
      const pos = Math.abs(rail.scrollLeft);
      if (prev) prev.disabled = pos <= 2;
      if (next) next.disabled = pos >= max;
    };

    prev?.addEventListener('click', () => rail.scrollBy({ left: step(), behavior: 'smooth' }));
    next?.addEventListener('click', () => rail.scrollBy({ left: -step(), behavior: 'smooth' }));
    rail.addEventListener('scroll', throttle(update, 120));
    window.addEventListener('resize', debounce(update, 200));
    update();
  }

  function initMegaMenu(root) {
    const trigger = $('[data-mega-trigger]', root);
    const panel = $('[data-mega-panel]', root);
    const content = $('[data-mega-content]', root);
    if (!trigger || !panel || root.__megaBound) return;
    root.__megaBound = true;

    let hideTimer;
    const cache = new Map();

    const open = () => {
      clearTimeout(hideTimer);
      panel.classList.remove('hidden');
      panel.classList.add('animate-fade-down');
      trigger.setAttribute('data-open', '');
      // Preload the first category so the panel is never blank.
      const first = $('[data-mega-item]', root);
      if (first && !content.dataset.loaded) load(first);
    };

    const close = () => {
      hideTimer = setTimeout(() => {
        panel.classList.add('hidden');
        trigger.removeAttribute('data-open');
      }, 180);
    };

    async function load(item) {
      const slug = item.dataset.megaItem;
      $$('[data-mega-item]', root).forEach((i) => i.removeAttribute('data-active'));
      item.setAttribute('data-active', '');
      content.dataset.loaded = '1';

      if (cache.has(slug)) {
        content.innerHTML = cache.get(slug);
        return;
      }

      content.innerHTML =
        '<div class="grid grid-cols-3 gap-6 p-2">' +
        Array(6).fill('<div class="space-y-2"><div class="skeleton h-4 w-2/3"></div><div class="skeleton h-3 w-1/2"></div><div class="skeleton h-3 w-3/5"></div></div>').join('') +
        '</div>';

      try {
        const data = await http.get(`/ajax/catalog/mega-menu/${slug}`, { quiet: true });
        cache.set(slug, data.html);
        content.innerHTML = data.html;
      } catch {
        content.innerHTML = '<p class="p-6 text-center text-sm text-ink-500">خطا در بارگذاری دسته‌بندی‌ها.</p>';
      }
    }

    trigger.addEventListener('mouseenter', open);
    trigger.addEventListener('click', (e) => {
      e.preventDefault();
      panel.classList.contains('hidden') ? open() : (panel.classList.add('hidden'), trigger.removeAttribute('data-open'));
    });

    root.addEventListener('mouseleave', close);
    panel.addEventListener('mouseenter', () => clearTimeout(hideTimer));

    $$('[data-mega-item]', root).forEach((item) => {
      item.addEventListener('mouseenter', () => load(item));
      item.addEventListener('focus', () => load(item));
    });

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        panel.classList.add('hidden');
        trigger.removeAttribute('data-open');
      }
    });
  }

  /** Auto-playing slide carousel (hero banners, testimonials). */
  function initCarousel(root) {
    if (root.__carouselBound) return;
    root.__carouselBound = true;

    const track = $('[data-carousel-track]', root);
    const slides = $$('[data-carousel-slide]', root);
    const dots = $$('[data-carousel-dot]', root);
    if (!track || slides.length < 1) return;

    const interval = Number(root.dataset.carouselInterval || 6000);
    let index = 0;
    let timer = null;

    const go = (i, smooth = true) => {
      index = (i + slides.length) % slides.length;
      track.style.transition = smooth ? 'transform .6s cubic-bezier(.4,0,.2,1)' : 'none';
      // RTL track: slides advance towards the positive X direction.
      track.style.transform = `translateX(${index * 100}%)`;
      dots.forEach((d, di) => {
        d.toggleAttribute('data-active', di === index);
        d.setAttribute('aria-current', di === index ? 'true' : 'false');
      });
      slides.forEach((s, si) => s.setAttribute('aria-hidden', si === index ? 'false' : 'true'));
    };

    const play = () => {
      if (slides.length < 2 || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
      stop();
      timer = setInterval(() => go(index + 1), interval);
    };
    const stop = () => timer && clearInterval(timer);

    $('[data-carousel-next]', root)?.addEventListener('click', () => { go(index + 1); play(); });
    $('[data-carousel-prev]', root)?.addEventListener('click', () => { go(index - 1); play(); });
    dots.forEach((dot, i) => dot.addEventListener('click', () => { go(i); play(); }));

    root.addEventListener('mouseenter', stop);
    root.addEventListener('mouseleave', play);

    // Touch swipe
    let startX = 0;
    root.addEventListener('touchstart', (e) => { startX = e.touches[0].clientX; stop(); }, { passive: true });
    root.addEventListener('touchend', (e) => {
      const delta = e.changedTouches[0].clientX - startX;
      if (Math.abs(delta) > 45) go(index + (delta > 0 ? -1 : 1));
      play();
    });

    document.addEventListener('visibilitychange', () => (document.hidden ? stop() : play()));

    go(0, false);
    play();
  }

  /** Count-down timer for the "special offers" strip. */
  function initCountdown(root = document) {
    $$('[data-countdown]', root).forEach((el) => {
      if (el.__cdBound) return;
      el.__cdBound = true;

      const target = new Date(el.dataset.countdown).getTime();
      const parts = {
        h: $('[data-cd-hours]', el),
        m: $('[data-cd-minutes]', el),
        s: $('[data-cd-seconds]', el),
      };

      const tick = () => {
        const diff = target - Date.now();
        if (diff <= 0) {
          if (parts.h) parts.h.textContent = '۰۰';
          if (parts.m) parts.m.textContent = '۰۰';
          if (parts.s) parts.s.textContent = '۰۰';
          clearInterval(id);
          return;
        }
        const totalHours = Math.floor(diff / 3600000);
        const minutes = Math.floor((diff % 3600000) / 60000);
        const seconds = Math.floor((diff % 60000) / 1000);
        const pad = (n) => faNumber(String(n).padStart(2, '0'));
        if (parts.h) parts.h.textContent = pad(totalHours);
        if (parts.m) parts.m.textContent = pad(minutes);
        if (parts.s) parts.s.textContent = pad(seconds);
      };

      tick();
      const id = setInterval(tick, 1000);
    });
  }

  /** Reveal-on-scroll for [data-reveal] elements. */
  function initReveal(root = document) {
    const items = $$('[data-reveal]:not([data-revealed])', root);
    if (!items.length) return;

    if (!('IntersectionObserver' in window)) {
      items.forEach((el) => el.setAttribute('data-revealed', ''));
      return;
    }

    const io = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry, i) => {
          if (!entry.isIntersecting) return;
          const delay = parseInt(entry.target.dataset.revealDelay || i * 60, 10);
          setTimeout(() => entry.target.setAttribute('data-revealed', ''), delay);
          io.unobserve(entry.target);
        });
      },
      { rootMargin: '0px 0px -8% 0px', threshold: 0.05 }
    );

    items.forEach((el) => io.observe(el));
  }

  /** Count-up animation for dashboard numbers. */
  function initCounters(root = document) {
    const items = $$('[data-count-to]:not([data-counted])', root);
    if (!items.length || !('IntersectionObserver' in window)) return;

    const io = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;
        const el = entry.target;
        el.dataset.counted = '1';
        io.unobserve(el);

        const target = parseFloat(enNumber(el.dataset.countTo).replace(/,/g, '')) || 0;
        const duration = 1100;
        const start = performance.now();

        const tick = (now) => {
          const p = Math.min(1, (now - start) / duration);
          const eased = 1 - Math.pow(1 - p, 3);
          el.textContent = money(Math.round(target * eased));
          if (p < 1) requestAnimationFrame(tick);
        };

        requestAnimationFrame(tick);
      });
    });

    items.forEach((el) => io.observe(el));
  }

  /* ===================================================================== */
  /* 9. Catalog filters (shop / category / search)                          */
  /* ===================================================================== */

  function initCatalog(root) {
    if (root.__catalogBound) return;
    root.__catalogBound = true;

    const grid = $('[data-product-grid]', root);
    const pager = $('[data-product-pagination]', root);
    const countLabel = $('[data-result-count]', root);
    const form = $('[data-filter-form]', root);
    if (!grid) return;

    const categorySlug = root.dataset.categorySlug || '';

    async function load(extra = {}, push = true) {
      const params = new URLSearchParams();

      if (form) {
        new FormData(form).forEach((value, key) => {
          if (value !== '' && value !== null) params.append(key, value);
        });
      }

      Object.entries(extra).forEach(([k, v]) => {
        params.delete(k);
        if (v !== '' && v !== null && v !== undefined) params.set(k, v);
      });

      if (categorySlug) params.set('category_slug', categorySlug);

      grid.style.opacity = '.45';
      grid.style.pointerEvents = 'none';

      try {
        const data = await http.get('/ajax/catalog/products?' + params.toString());
        grid.innerHTML = data.html;
        if (pager) pager.innerHTML = data.pagination || '';
        if (countLabel) countLabel.textContent = data.count_label;
        bindAll(grid);
        initReveal(grid);

        if (push) {
          params.delete('category_slug');
          const qs = params.toString();
          history.pushState({ catalog: true }, '', qs ? '?' + qs : window.location.pathname);
        }

        root.scrollIntoView({ behavior: 'smooth', block: 'start' });
      } catch (err) {
        toast(err.message, 'error');
      } finally {
        grid.style.opacity = '';
        grid.style.pointerEvents = '';
      }
    }

    // Any change inside the filter form refreshes the grid.
    form?.addEventListener('change', (e) => {
      if (e.target.matches('[data-no-auto]')) return;
      load({ page: 1 });
    });

    // Price inputs are debounced.
    $$('[data-price-input]', root).forEach((input) => {
      input.addEventListener(
        'input',
        debounce(() => load({ page: 1 }), 700)
      );
    });

    // Sort select.
    $('[data-sort-select]', root)?.addEventListener('change', (e) => load({ sort: e.target.value, page: 1 }));

    // Pagination links.
    root.addEventListener('click', (e) => {
      const link = e.target.closest('[data-page]');
      if (link) {
        e.preventDefault();
        load({ page: link.dataset.page });
      }

      const clear = e.target.closest('[data-clear-filters]');
      if (clear) {
        e.preventDefault();
        form?.reset();
        $$('input[type=checkbox]', form).forEach((cb) => (cb.checked = false));
        $$('input[type=text], input[type=number]', form).forEach((i) => (i.value = ''));
        load({ page: 1 });
      }

      const chip = e.target.closest('[data-remove-filter]');
      if (chip) {
        e.preventDefault();
        const [name, value] = chip.dataset.removeFilter.split('=');
        const input = form?.querySelector(`[name="${name}"][value="${value}"], [name="${name}[]"][value="${value}"]`);
        if (input) input.checked = false;
        else if (form?.elements[name]) form.elements[name].value = '';
        load({ page: 1 });
      }
    });

    // Grid / list view toggle.
    $$('[data-view-mode]', root).forEach((btn) => {
      btn.addEventListener('click', () => {
        const mode = btn.dataset.viewMode;
        $$('[data-view-mode]', root).forEach((b) => b.removeAttribute('data-active'));
        btn.setAttribute('data-active', '');
        grid.dataset.view = mode;
        localStorage.setItem('dg.view', mode);
      });
    });

    const savedView = localStorage.getItem('dg.view');
    if (savedView) {
      grid.dataset.view = savedView;
      $(`[data-view-mode="${savedView}"]`, root)?.setAttribute('data-active', '');
    }

    window.addEventListener('popstate', () => window.location.reload());
  }

  /* ===================================================================== */
  /* 10. Product page                                                       */
  /* ===================================================================== */

  function initProductPage(root) {
    if (root.__productBound) return;
    root.__productBound = true;

    const mainImage = $('[data-gallery-main]', root);
    const slug = root.dataset.productSlug;

    // Gallery
    $$('[data-gallery-thumb]', root).forEach((thumb) => {
      const activate = () => {
        if (!mainImage) return;
        mainImage.style.opacity = '0';
        setTimeout(() => {
          mainImage.src = thumb.dataset.galleryThumb;
          mainImage.style.opacity = '1';
        }, 140);
        $$('[data-gallery-thumb]', root).forEach((t) => t.removeAttribute('data-active'));
        thumb.setAttribute('data-active', '');
      };
      thumb.addEventListener('click', activate);
      thumb.addEventListener('mouseenter', activate);
    });

    // Gallery arrows
    const thumbs = $$('[data-gallery-thumb]', root);
    let galleryIndex = 0;
    $('[data-gallery-prev]', root)?.addEventListener('click', () => {
      galleryIndex = (galleryIndex - 1 + thumbs.length) % thumbs.length;
      thumbs[galleryIndex]?.click();
    });
    $('[data-gallery-next]', root)?.addEventListener('click', () => {
      galleryIndex = (galleryIndex + 1) % thumbs.length;
      thumbs[galleryIndex]?.click();
    });

    // Variant pickers — recompute price and stock server-side.
    const variantInput = $('[data-variant-id]', root);

    $$('[data-variant-option]', root).forEach((btn) => {
      btn.addEventListener('click', async () => {
        const group = btn.dataset.variantGroup;
        $$(`[data-variant-option][data-variant-group="${group}"]`, root).forEach((b) =>
          b.removeAttribute('data-active')
        );
        btn.setAttribute('data-active', '');

        const label = $(`[data-variant-label="${group}"]`, root);
        if (label) label.textContent = btn.dataset.variantLabel || '';

        if (variantInput) variantInput.value = btn.dataset.variantOption;

        if (!slug) return;

        try {
          const data = await http.get(
            `/ajax/catalog/variant-price/${slug}?variant_id=${btn.dataset.variantOption}`,
            { quiet: true }
          );

          const priceEl = $('[data-product-price]', root);
          if (priceEl) {
            priceEl.textContent = data.price;
            priceEl.classList.remove('animate-count-flip');
            void priceEl.offsetWidth;
            priceEl.classList.add('animate-count-flip');
          }

          const instEl = $('[data-product-installment]', root);
          if (instEl) instEl.textContent = data.installment;

          const stockEl = $('[data-product-stock]', root);
          if (stockEl) {
            stockEl.textContent = data.stock_label;
            stockEl.className = data.available
              ? 'badge-green'
              : 'badge-gray';
          }

          const addBtn = $('[data-product-add]', root);
          if (addBtn) {
            addBtn.disabled = !data.available;
            addBtn.classList.toggle('opacity-50', !data.available);
          }
        } catch (err) {
          toast(err.message, 'error');
        }
      });
    });

    // Sticky add-to-cart bar appears once the main CTA scrolls away.
    const cta = $('[data-product-add]', root);
    const stickyBar = $('[data-sticky-bar]');

    if (cta && stickyBar && 'IntersectionObserver' in window) {
      new IntersectionObserver(
        ([entry]) => {
          stickyBar.classList.toggle('translate-y-full', entry.isIntersecting);
          stickyBar.classList.toggle('opacity-0', entry.isIntersecting);
        },
        { threshold: 0 }
      ).observe(cta);
    }

    // Deep-link to a tab (#reviews)
    if (window.location.hash) {
      const tab = $(`[data-tab-hash="${window.location.hash.slice(1)}"]`, root);
      tab?.click();
    }
  }

  /* ===================================================================== */
  /* 11. Misc widgets                                                       */
  /* ===================================================================== */

  /** Sticky header shadow + back-to-top button. */
  function initScrollUi() {
    const header = $('[data-header]');
    const toTop = $('#dg-to-top');

    const onScroll = throttle(() => {
      const y = window.scrollY;
      header?.classList.toggle('shadow-card', y > 8);

      if (toTop) {
        const show = y > 500;
        toTop.classList.toggle('opacity-0', !show);
        toTop.classList.toggle('translate-y-4', !show);
        toTop.classList.toggle('pointer-events-none', !show);
      }
    }, 100);

    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();

    toTop?.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
  }

  /** Mobile off-canvas nav (storefront drawer or admin drawer). */
  function initMobileNav() {
    const nav = $('#dg-mobile-nav') || $('#dg-admin-nav');
    if (!nav) return;

    const panel = $('[data-mobile-nav-panel]', nav);

    const open = () => {
      nav.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
      panel?.classList.add('animate-slide-in-start');
    };

    const close = () => {
      panel?.classList.remove('animate-slide-in-start');
      nav.classList.add('hidden');
      document.body.style.overflow = '';
    };

    document.addEventListener('click', (e) => {
      if (e.target.closest('[data-mobile-nav-open]')) open();
      if (e.target.closest('[data-mobile-nav-close]')) close();
    });

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && !nav.classList.contains('hidden')) close();
    });
  }

  /** Copy-to-clipboard buttons. */
  function initCopy(root = document) {
    $$('[data-copy]', root).forEach((btn) => {
      if (btn.__copyBound) return;
      btn.__copyBound = true;
      btn.addEventListener('click', async () => {
        try {
          await navigator.clipboard.writeText(btn.dataset.copy);
          toast('در حافظه کپی شد.', 'success', 2000);
        } catch {
          toast('کپی کردن ممکن نشد.', 'error');
        }
      });
    });
  }

  /** Dual price range slider used by the catalog sidebar. */
  function initPriceRange(root = document) {
    $$('[data-range]', root).forEach((wrap) => {
      if (wrap.__rangeBound) return;
      wrap.__rangeBound = true;

      const min = Number(wrap.dataset.rangeMin || 0);
      const max = Number(wrap.dataset.rangeMax || 100000000);
      const minInput = $('[data-range-min]', wrap);
      const maxInput = $('[data-range-max]', wrap);
      const fill = $('[data-range-fill]', wrap);

      const paint = () => {
        const lo = Number(enNumber(minInput.value).replace(/\D/g, '')) || min;
        const hi = Number(enNumber(maxInput.value).replace(/\D/g, '')) || max;
        const a = Math.max(0, Math.min(100, ((lo - min) / (max - min || 1)) * 100));
        const b = Math.max(0, Math.min(100, ((hi - min) / (max - min || 1)) * 100));
        if (fill) {
          fill.style.insetInlineStart = Math.min(a, b) + '%';
          fill.style.width = Math.abs(b - a) + '%';
        }
      };

      [minInput, maxInput].forEach((input) => {
        input?.addEventListener('input', () => {
          input.value = money(enNumber(input.value).replace(/\D/g, ''));
          paint();
        });
      });

      paint();
    });
  }

  /** Persian-digit friendly numeric inputs. */
  function initNumericInputs(root = document) {
    $$('[data-numeric]', root).forEach((input) => {
      if (input.__numBound) return;
      input.__numBound = true;
      input.setAttribute('inputmode', 'numeric');
      input.addEventListener('input', () => {
        const raw = enNumber(input.value).replace(/[^\d]/g, '');
        input.value = raw ? money(raw) : '';
      });
    });
  }

  /** Star rating picker in the review form. */
  function initRatingPicker(root = document) {
    $$('[data-rating-picker]', root).forEach((picker) => {
      if (picker.__rateBound) return;
      picker.__rateBound = true;

      const input = $('input[type=hidden]', picker);
      const stars = $$('[data-rating-value]', picker);
      const label = $('[data-rating-label]', picker.parentElement || picker);
      const labels = ['خیلی بد', 'بد', 'متوسط', 'خوب', 'عالی'];

      const paint = (value) => {
        stars.forEach((s) => {
          const v = Number(s.dataset.ratingValue);
          s.classList.toggle('text-star', v <= value);
          s.classList.toggle('text-ink-300', v > value);
        });
        if (label) label.textContent = value ? labels[value - 1] : '';
      };

      stars.forEach((star) => {
        star.addEventListener('mouseenter', () => paint(Number(star.dataset.ratingValue)));
        star.addEventListener('click', () => {
          input.value = star.dataset.ratingValue;
          paint(Number(star.dataset.ratingValue));
        });
      });

      picker.addEventListener('mouseleave', () => paint(Number(input.value || 0)));
      paint(Number(input.value || 0));
    });
  }

  /** Repeatable rows (product attributes / variants in the admin forms). */
  function initRepeater(root = document) {
    $$('[data-repeater]', root).forEach((rep) => {
      if (rep.__repBound) return;
      rep.__repBound = true;

      const list = $('[data-repeater-list]', rep);
      const tpl = $('[data-repeater-template]', rep);

      $('[data-repeater-add]', rep)?.addEventListener('click', () => {
        const index = list.children.length;
        const html = tpl.innerHTML.replace(/__INDEX__/g, index);
        const wrap = document.createElement('div');
        wrap.innerHTML = html;
        const node = wrap.firstElementChild;
        node.classList.add('animate-fade-up');
        list.appendChild(node);
        bindAll(node);
      });

      rep.addEventListener('click', (e) => {
        const remove = e.target.closest('[data-repeater-remove]');
        if (!remove) return;
        const row = remove.closest('[data-repeater-row]');
        row.style.transition = 'all .25s';
        row.style.opacity = '0';
        row.style.transform = 'translateX(-16px)';
        setTimeout(() => row.remove(), 250);
      });
    });
  }

  /** Toggle-all checkbox for admin tables. */
  function initBulkSelect(root = document) {
    $$('[data-bulk-root]', root).forEach((wrap) => {
      if (wrap.__bulkBound) return;
      wrap.__bulkBound = true;

      const all = $('[data-bulk-all]', wrap);
      const bar = $('[data-bulk-bar]', wrap);

      const refresh = () => {
        const boxes = $$('[data-bulk-item]', wrap);
        const checked = boxes.filter((b) => b.checked);
        if (all) {
          all.checked = boxes.length > 0 && checked.length === boxes.length;
          all.indeterminate = checked.length > 0 && checked.length < boxes.length;
        }
        bar?.classList.toggle('hidden', checked.length === 0);
        $$('[data-bulk-count]', wrap).forEach((el) => (el.textContent = faNumber(checked.length)));
      };

      all?.addEventListener('change', () => {
        $$('[data-bulk-item]', wrap).forEach((b) => (b.checked = all.checked));
        refresh();
      });

      wrap.addEventListener('change', (e) => {
        if (e.target.matches('[data-bulk-item]')) refresh();
      });

      wrap.__bulkRefresh = refresh;
      refresh();
    });
  }

  /** Minimal SVG line/bar chart for the admin dashboard (no chart library). */
  function initCharts(root = document) {
    $$('[data-chart]', root).forEach((el) => {
      if (el.__chartBound) return;
      el.__chartBound = true;
      renderChart(el);
      window.addEventListener('resize', debounce(() => renderChart(el), 250));
    });
  }

  function renderChart(el) {
    let series;
    try {
      series = JSON.parse(el.dataset.chart || '[]');
    } catch {
      return;
    }
    if (!series.length) return;

    const key = el.dataset.chartKey || 'revenue';
    const w = el.clientWidth || 640;
    const h = Number(el.dataset.chartHeight || 220);
    const padX = 8;
    const padY = 18;
    const values = series.map((d) => Number(d[key] || 0));
    const max = Math.max(...values, 1);
    const stepX = (w - padX * 2) / Math.max(1, series.length - 1);

    const points = series.map((d, i) => {
      const x = padX + i * stepX;
      const y = h - padY - ((Number(d[key] || 0) / max) * (h - padY * 2));
      return [x, y];
    });

    // Smooth path (Catmull-Rom → cubic Bézier)
    let path = `M ${points[0][0]},${points[0][1]}`;
    for (let i = 0; i < points.length - 1; i++) {
      const p0 = points[i === 0 ? 0 : i - 1];
      const p1 = points[i];
      const p2 = points[i + 1];
      const p3 = points[i + 2] || p2;
      const c1x = p1[0] + (p2[0] - p0[0]) / 6;
      const c1y = p1[1] + (p2[1] - p0[1]) / 6;
      const c2x = p2[0] - (p3[0] - p1[0]) / 6;
      const c2y = p2[1] - (p3[1] - p1[1]) / 6;
      path += ` C ${c1x},${c1y} ${c2x},${c2y} ${p2[0]},${p2[1]}`;
    }

    const area = `${path} L ${points[points.length - 1][0]},${h - padY} L ${points[0][0]},${h - padY} Z`;
    const gid = 'dgc' + Math.random().toString(36).slice(2, 8);

    el.innerHTML = `
      <svg viewBox="0 0 ${w} ${h}" class="w-full" style="height:${h}px" preserveAspectRatio="none">
        <defs>
          <linearGradient id="${gid}" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stop-color="#EF394E" stop-opacity=".22"/>
            <stop offset="100%" stop-color="#EF394E" stop-opacity="0"/>
          </linearGradient>
        </defs>
        ${[0.25, 0.5, 0.75].map((f) => `<line x1="0" y1="${padY + (h - padY * 2) * f}" x2="${w}" y2="${padY + (h - padY * 2) * f}" stroke="#E5E5E7" stroke-dasharray="4 6"/>`).join('')}
        <path d="${area}" fill="url(#${gid})"/>
        <path d="${path}" fill="none" stroke="#EF394E" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
              stroke-dasharray="2200" stroke-dashoffset="2200">
          <animate attributeName="stroke-dashoffset" from="2200" to="0" dur="1.2s" fill="freeze"
                   calcMode="spline" keySplines=".25 .1 .25 1"/>
        </path>
        ${points.map((p, i) => `<circle cx="${p[0]}" cy="${p[1]}" r="3.5" fill="#fff" stroke="#EF394E" stroke-width="2" opacity="0"><animate attributeName="opacity" from="0" to="1" dur=".3s" begin="${0.6 + i * 0.03}s" fill="freeze"/><title>${series[i].label}: ${money(series[i][key])}</title></circle>`).join('')}
      </svg>
      <div class="mt-1 flex justify-between px-1 text-[10px] text-ink-400">
        <span>${faNumber(series[0].short)}</span>
        <span>${faNumber(series[Math.floor(series.length / 2)].short)}</span>
        <span>${faNumber(series[series.length - 1].short)}</span>
      </div>`;
  }

  /* ===================================================================== */
  /* 12. Global delegated events                                            */
  /* ===================================================================== */

  document.addEventListener('click', async (e) => {
    // ---- add to cart
    const addBtn = e.target.closest('[data-add-to-cart]');
    if (addBtn) {
      e.preventDefault();
      const qtyField = document.querySelector('[data-qty-input]');
      const variant = document.querySelector('[data-variant-id]')?.value || null;
      cart.add(
        addBtn.dataset.addToCart,
        addBtn.dataset.quantity ? Number(addBtn.dataset.quantity) : Number(qtyField?.value || 1),
        addBtn.hasAttribute('data-use-variant') ? variant : addBtn.dataset.variantId || null,
        addBtn
      );
      return;
    }

    // ---- cart line controls
    const inc = e.target.closest('[data-qty-inc]');
    if (inc) {
      const id = inc.dataset.qtyInc;
      const current = Number(enNumber($(`[data-qty-value="${id}"]`)?.textContent || '1'));
      cart.update(id, current + 1);
      return;
    }

    const dec = e.target.closest('[data-qty-dec]');
    if (dec) {
      const id = dec.dataset.qtyDec;
      const current = Number(enNumber($(`[data-qty-value="${id}"]`)?.textContent || '1'));
      if (current <= 1) {
        const ok = await modal.confirm('این کالا از سبد خرید شما حذف شود؟', {
          title: 'حذف کالا',
          accept: 'حذف کن',
        });
        if (ok) cart.remove(id);
      } else {
        cart.update(id, current - 1);
      }
      return;
    }

    const removeBtn = e.target.closest('[data-cart-remove]');
    if (removeBtn) {
      const ok = await modal.confirm('این کالا از سبد خرید شما حذف شود؟', {
        title: 'حذف کالا',
        accept: 'حذف کن',
      });
      if (ok) cart.remove(removeBtn.dataset.cartRemove);
      return;
    }

    if (e.target.closest('[data-cart-remove-selected]')) {
      cart.removeSelected();
      return;
    }

    const couponRemove = e.target.closest('[data-coupon-remove]');
    if (couponRemove) {
      cart.removeCoupon();
      return;
    }

    // ---- wishlist
    const wishBtn = e.target.closest('[data-wishlist-toggle]');
    if (wishBtn) {
      e.preventDefault();
      toggleWishlist(wishBtn.dataset.wishlistToggle, wishBtn);
      return;
    }

    // ---- quick view
    const qv = e.target.closest('[data-quick-view]');
    if (qv) {
      e.preventDefault();
      try {
        const data = await http.get(`/ajax/catalog/quick-view/${qv.dataset.quickView}`);
        modal.show(data.title, data.html);
      } catch (err) {
        toast(err.message, 'error');
      }
      return;
    }

    // ---- review helpfulness
    const vote = e.target.closest('[data-review-vote]');
    if (vote) {
      try {
        const data = await http.post(`/ajax/reviews/${vote.dataset.reviewVote}/vote`, {
          type: vote.dataset.voteType,
        });
        const likes = $(`[data-vote-likes="${vote.dataset.reviewVote}"]`);
        const dislikes = $(`[data-vote-dislikes="${vote.dataset.reviewVote}"]`);
        if (likes) likes.textContent = faNumber(data.likes);
        if (dislikes) dislikes.textContent = faNumber(data.dislikes);
        toast(data.message, 'success', 2000);
      } catch (err) {
        toast(err.message, 'error');
      }
      return;
    }

    // ---- generic delete / action button:
    //      <button data-action="/url" data-method="DELETE" data-confirm="…">
    const action = e.target.closest('[data-action]');
    if (action) {
      e.preventDefault();

      if (action.dataset.confirm) {
        const ok = await modal.confirm(action.dataset.confirm, {
          title: action.dataset.confirmTitle || 'تأیید عملیات',
          accept: action.dataset.confirmAccept || 'تأیید',
        });
        if (!ok) return;
      }

      action.disabled = true;

      try {
        const data = await request(action.dataset.action, {
          method: (action.dataset.method || 'POST').toUpperCase(),
          body: action.dataset.payload ? JSON.parse(action.dataset.payload) : {},
        });

        toast(data.message, 'success');
        document.dispatchEvent(new CustomEvent('dg:action-done', { detail: { action, data } }));

        if (action.dataset.removeRow) {
          const row = action.closest(action.dataset.removeRow);
          if (row) {
            row.style.transition = 'all .3s';
            row.style.opacity = '0';
            setTimeout(() => row.remove(), 300);
          }
        }

        const target = data.redirect || action.dataset.redirect;

        if (target) setTimeout(() => (window.location.href = target), 500);
        else if (action.hasAttribute('data-reload')) setTimeout(() => window.location.reload(), 600);

        if (action.dataset.toggleText) {
          const [on, off] = action.dataset.toggleText.split('|');
          action.textContent = data.is_active ? on : off;
        }
      } catch (err) {
        toast(err.message, 'error');
      } finally {
        action.disabled = false;
      }
      return;
    }
  });

  document.addEventListener('change', (e) => {
    const select = e.target.closest('[data-cart-select]');
    if (select) {
      cart.select(select.dataset.cartSelect, select.checked);
      return;
    }

    const all = e.target.closest('[data-cart-select-all]');
    if (all) {
      cart.selectAll(all.checked);
    }
  });

  document.addEventListener('submit', (e) => {
    const form = e.target.closest('[data-ajax-form]');
    if (!form) return;
    e.preventDefault();

    if (form.hasAttribute('data-coupon-submit')) {
      const input = form.querySelector('[name="code"]');
      cart.applyCoupon(input.value, form);
      return;
    }

    submitAjaxForm(form);
  });

  /* ===================================================================== */
  /* 13. Bootstrapping                                                      */
  /* ===================================================================== */

  function bindAll(root = document) {
    $$('[data-dropdown]', root).forEach(initDropdown);
    $$('[data-rail-root]', root).forEach(initRail);
    $$('[data-mega-root]', root).forEach(initMegaMenu);
    $$('[data-search-root]', root).forEach(initSearch);
    $$('[data-tabs]', root).forEach(initTabs);
    $$('[data-catalog]', root).forEach(initCatalog);
    $$('[data-product-page]', root).forEach(initProductPage);
    $$('[data-carousel]', root).forEach(initCarousel);
    initCountdown(root);
    initReveal(root);
    initCounters(root);
    initCopy(root);
    initPriceRange(root);
    initNumericInputs(root);
    initRatingPicker(root);
    initRepeater(root);
    initBulkSelect(root);
    initCharts(root);
  }

  function boot() {
    bindAll(document);
    initScrollUi();
    initMobileNav();

    // Load the mini-cart the first time the dropdown opens.
    const miniRoot = $('[data-mini-cart-root]');
    if (miniRoot) {
      let loaded = false;
      miniRoot.addEventListener('dg:dropdown-open', () => {
        if (!loaded) {
          loaded = true;
          cart.loadMini();
        }
      });
      document.addEventListener('dg:cart-updated', () => {
        loaded = false;
      });
    }

    // Announce readiness for page-level scripts.
    document.dispatchEvent(new CustomEvent('dg:ready'));
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
  } else {
    boot();
  }

  /* ===================================================================== */
  /* 14. Public API                                                         */
  /* ===================================================================== */

  window.dg = {
    $, $$, http, request, toast, modal, cart, bindAll,
    faNumber, enNumber, money, debounce, throttle,
    toggleWishlist, submitAjaxForm, initReveal, initCharts, renderChart, initCarousel,
  };
})();
