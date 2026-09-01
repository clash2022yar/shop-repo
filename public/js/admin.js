/*!
 * دیجی‌نو — admin panel helpers
 * -----------------------------------------------------------------------------
 * Small, dependency-free utilities that sit on top of window.dg and power the
 * repeated interaction patterns of the admin panel:
 *
 *   dgCrud()   modal driven create/edit forms (categories, brands, coupons, …)
 *   dgTable()  AJAX filtering / sorting / paging for admin index tables
 *
 * No framework, no build step — plain ES2019 that ships as-is.
 */
(function () {
  'use strict';

  /**
   * Wire a "one modal, two modes" CRUD form.
   *
   * @param {Object} options
   * @param {string} options.modal       modal id used by <x-modal id="…">
   * @param {string} options.form        css selector of the <form> inside it
   * @param {string} options.storeUrl    POST endpoint for new records
   * @param {string} options.baseUrl     collection url, "/admin/brands" → PUT /admin/brands/{id}
   * @param {string} options.resource    key of the record inside the show() JSON payload
   * @param {string[]} options.fields    plain input names copied from the payload
   * @param {string[]} [options.booleans] checkbox input names
   * @param {string}  [options.createTitle]
   * @param {Function} [options.onEdit]  extra hook: (record, form) => void
   */
  window.dgCrud = function dgCrud(options) {
    var form = document.querySelector(options.form);
    if (!form) return;

    var host = document.querySelector('[data-modal="' + options.modal + '"]');
    var titleEl = host ? host.querySelector('[data-modal-title]') : null;
    var createTitle = options.createTitle || 'ثبت مورد جدید';
    var booleans = options.booleans || [];

    function clearErrors() {
      form.querySelectorAll('.error-text').forEach(function (el) { el.textContent = ''; });
      form.querySelectorAll('.field-error').forEach(function (el) { el.classList.remove('field-error'); });
    }

    function toCreate() {
      form.reset();
      clearErrors();
      form.setAttribute('action', options.storeUrl);
      form.dataset.method = 'POST';
      if (titleEl) titleEl.textContent = createTitle;
    }

    function fill(record) {
      clearErrors();

      (options.fields || []).forEach(function (name) {
        var input = form.querySelector('[name="' + name + '"]:not([type=hidden])');
        if (!input) return;

        var value = record[name];

        if (input.type === 'datetime-local' && value) {
          value = String(value).replace(' ', 'T').slice(0, 16);
        } else if (input.type === 'date' && value) {
          value = String(value).slice(0, 10);
        }

        input.value = value === null || value === undefined ? '' : value;
      });

      booleans.forEach(function (name) {
        var box = form.querySelector('[name="' + name + '"][type=checkbox]');
        if (box) box.checked = !!record[name];
      });

      if (typeof options.onEdit === 'function') options.onEdit(record, form);
    }

    // "new" buttons -------------------------------------------------------
    document.querySelectorAll('[data-crud-new="' + options.modal + '"]').forEach(function (btn) {
      btn.addEventListener('click', toCreate);
    });

    // "edit" buttons ------------------------------------------------------
    document.addEventListener('click', function (e) {
      var btn = e.target.closest('[data-crud-edit="' + options.modal + '"]');
      if (!btn) return;

      e.preventDefault();

      var url = btn.dataset.crudUrl;

      dg.http
        .get(url)
        .then(function (data) {
          var record = data[options.resource] || data.record || data;
          form.reset();
          fill(record);
          form.setAttribute('action', options.baseUrl.replace(/\/$/, '') + '/' + record.id);
          form.dataset.method = 'PUT';
          if (titleEl) titleEl.textContent = btn.dataset.crudTitle || 'ویرایش';
          dg.modal.open(options.modal);
        })
        .catch(function (err) { dg.toast(err.message, 'error'); });
    });
  };

  /**
   * AJAX table: filters + pagination without a page reload.
   *
   * @param {Object} options
   * @param {string} options.root   wrapper selector (contains form, rows, pagination)
   * @param {string} options.url    endpoint returning {html, pagination, total}
   * @param {Function} [options.onLoad]
   */
  window.dgTable = function dgTable(options) {
    var root = document.querySelector(options.root);
    if (!root) return;

    var form = root.querySelector('[data-admin-filter]');
    var rows = root.querySelector('[data-admin-rows]');
    var pager = root.querySelector('[data-admin-pagination]');
    var totalEls = root.querySelectorAll('[data-admin-total]');

    function load(extra) {
      var params = new URLSearchParams(form ? new FormData(form) : '');

      Object.keys(extra || {}).forEach(function (key) {
        if (extra[key] === '' || extra[key] === null) params.delete(key);
        else params.set(key, extra[key]);
      });

      rows.style.opacity = '.45';

      return dg.http
        .get(options.url + (options.url.indexOf('?') > -1 ? '&' : '?') + params.toString())
        .then(function (data) {
          rows.innerHTML = data.html || '';
          if (pager) pager.innerHTML = data.pagination || '';
          totalEls.forEach(function (el) { el.textContent = dg.faNumber(data.total || 0); });
          dg.bindAll(rows);
          history.replaceState(null, '', location.pathname + '?' + params.toString());
          if (typeof options.onLoad === 'function') options.onLoad(data);
        })
        .catch(function (err) { dg.toast(err.message, 'error'); })
        .finally(function () { rows.style.opacity = ''; });
    }

    if (form) {
      form.addEventListener('submit', function (e) { e.preventDefault(); load({ page: 1 }); });
      form.addEventListener('change', function () { load({ page: 1 }); });

      var search = form.querySelector('[type=search]');
      if (search) search.addEventListener('input', dg.debounce(function () { load({ page: 1 }); }, 450));
    }

    // status pills
    root.querySelectorAll('[data-status-tab]').forEach(function (tab) {
      tab.addEventListener('click', function () {
        root.querySelectorAll('[data-status-tab]').forEach(function (t) { t.removeAttribute('data-active'); });
        tab.setAttribute('data-active', '');

        var hidden = form && form.querySelector('[name="' + (tab.dataset.statusName || 'status') + '"]');
        if (hidden) hidden.value = tab.dataset.statusTab;

        load({ page: 1 });
      });
    });

    // pagination
    root.addEventListener('click', function (e) {
      var link = e.target.closest('[data-page]');
      if (!link) return;
      e.preventDefault();
      load({ page: link.dataset.page });
      root.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });

    root.__reload = load;
    return load;
  };
})();
