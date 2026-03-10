/**
 * properties.js — AJAX property listing & filtering module
 * Exported as two global objects: PropertiesAjax and PropertiesListing
 */

/* ================================================================
   Helpers
   ================================================================ */
function escHtml(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
}

function cfg(key) {
    return (window.PROPS_CONFIG && window.PROPS_CONFIG.labels)
        ? (window.PROPS_CONFIG.labels[key] || '')
        : '';
}

/* ================================================================
   PropertiesAjax — low-level fetch + card renderer
   ================================================================ */
window.PropertiesAjax = (function () {

    function load(params, callback) {
        const apiUrl = (window.PROPS_CONFIG && window.PROPS_CONFIG.apiUrl) || '/api/properties';
        const qs     = new URLSearchParams();

        const locale = (window.PROPS_CONFIG && window.PROPS_CONFIG.locale)
            || document.documentElement.lang
            || 'en';
        qs.set('lang', locale);

        Object.entries(params || {}).forEach(function ([k, v]) {
            if (v !== null && v !== undefined && v !== '') {
                qs.set(k, v);
            }
        });

        const url = apiUrl + (qs.toString() ? '?' + qs.toString() : '');

        fetch(url, {
            headers: {
                'Accept':           'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin',
        })
        .then(function (res) {
            if (!res.ok) throw new Error('HTTP ' + res.status);
            return res.json();
        })
        .then(function (json) {
            window._propsMeta = json.meta || null;
            callback(json.data || [], json.meta || null, json.links || null);
        })
        .catch(function (err) {
            console.error('[PropertiesAjax] fetch error:', err);
            callback([], null, null);
        });
    }

    /**
     * Build HTML for a property card matching components/property-card.blade.php
     */
    function cardHtml(p) {
        var opCode   = (p.operation_type && p.operation_type.code) ? p.operation_type.code : '';
        var opName   = (p.operation_type && p.operation_type.name) ? escHtml(p.operation_type.name) : '';
        var typeName = (p.property_type  && p.property_type.name)  ? escHtml(p.property_type.name)  : '';
        var cityName = (p.city           && p.city.name)           ? escHtml(p.city.name)            : '';
        var currency = (p.currency       && p.currency.name)       ? escHtml(p.currency.name)        : '';
        var address  = p.address  ? escHtml(p.address)  : cityName;
        var title    = p.title    ? escHtml(p.title)    : '';
        var price    = p.price_formatted ? escHtml(p.price_formatted) : '';
        var url      = p.url      ? escHtml(p.url)      : '#';
        var imgSrc   = p.main_image ? escHtml(p.main_image) : '';
        var created  = p.created_at ? escHtml(p.created_at) : '';

        var imgHtml = imgSrc
            ? '<img src="' + imgSrc + '" alt="' + title + '" class="prop-card__img" loading="lazy">'
            : '<div class="prop-card__img-placeholder"><i class="bi bi-buildings text-secondary" style="font-size:2.5rem"></i></div>';

        /* Badges */
        var leftBadges = '';
        if (p.is_featured) {
            leftBadges += '<span class="badge prop-badge prop-badge--featured"><i class="bi bi-star-fill me-1"></i>' + escHtml(cfg('featured')) + '</span> ';
        }
        if (opCode) {
            leftBadges += '<span class="badge prop-badge prop-badge--' + escHtml(opCode) + '">' + opName + '</span>';
        }

        var rightBadge = typeName
            ? '<span class="badge prop-badge prop-badge--type">' + typeName + '</span>'
            : '';

        /* Specs */
        var specs = '';
        if (p.area) {
            specs += '<li><i class="bi bi-aspect-ratio"></i> ' + parseInt(p.area).toLocaleString() + ' ' + escHtml(cfg('m2')) + '</li>';
        }
        if (p.rooms) {
            specs += '<li><i class="bi bi-door-open"></i> ' + escHtml(String(p.rooms)) + ' ' + escHtml(cfg('rooms')) + '</li>';
        }
        if (p.bathrooms) {
            specs += '<li><i class="bi bi-droplet"></i> ' + escHtml(String(p.bathrooms)) + ' ' + escHtml(cfg('bathrooms')) + '</li>';
        }

        return '<article class="prop-card">'
            + '<div class="prop-card__img-wrap">'
            +   '<a href="' + url + '">' + imgHtml + '</a>'
            +   '<div class="prop-card__badges">'
            +     '<div class="d-flex flex-column gap-1">' + leftBadges + '</div>'
            +     rightBadge
            +   '</div>'
            + '</div>'
            + '<div class="prop-card__body">'
            +   '<div class="prop-card__price">' + price
            +     (currency ? '<span class="prop-card__price-currency"> ' + currency + '</span>' : '')
            +   '</div>'
            +   '<h3 class="prop-card__title"><a href="' + url + '">' + title + '</a></h3>'
            +   (address
                  ? '<p class="prop-card__location"><i class="bi bi-geo-alt-fill text-danger"></i> ' + address + '</p>'
                  : '')
            +   (specs
                  ? '<ul class="prop-card__specs">' + specs + '</ul>'
                  : '')
            + '</div>'
            + '<div class="prop-card__footer">'
            +   '<span>' + created + '</span>'
            +   (typeName ? '<span class="prop-card__type-tag">' + typeName + '</span>' : '')
            + '</div>'
            + '</article>';
    }

    return { load: load, cardHtml: cardHtml };

}());

/* ================================================================
   PropertiesListing — full listing-page controller
   ================================================================ */
window.PropertiesListing = (function () {

    var _cfg = {};
    var _currentParams = {};
    var _currentPage = 1;
    var _lastPage = 1;
    var _isLoading = false;

    function init(options) {
        _cfg = options;
        _currentParams = _readInitialFilters();

        _bindFilterForm();
        _bindViewToggle();
        _bindLoadMore();
        _bindInfiniteScroll();
        _fetchAndRender(_currentParams, 1, false);
    }

    /* ── read initial filters from PHP-rendered DTO ── */
    function _readInitialFilters() {
        var init = (window.PROPS_CONFIG && window.PROPS_CONFIG.filters) ? window.PROPS_CONFIG.filters : {};
        var params = {};
        ['cityId', 'operationTypeId', 'propertyTypeId', 'priceMin', 'priceMax',
         'areaMin', 'areaMax', 'rooms', 'query', 'sortBy'].forEach(function (k) {
            if (init[k] != null && init[k] !== '') {
                var apiKey = k.replace(/([A-Z])/g, '_$1').toLowerCase();
                if (k === 'query') apiKey = 'q';
                params[apiKey] = init[k];
            }
        });
        return params;
    }

    /* ── bind filter form submit ── */
    function _bindFilterForm() {
        var form = document.getElementById(_cfg.formId);
        if (!form) return;

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            _currentParams = _formToParams(form);
            _fetchAndRender(_currentParams, 1, false);
            _updateUrl(_currentParams, 1);
        });

        form.querySelectorAll('select').forEach(function (sel) {
            sel.addEventListener('change', function () {
                form.dispatchEvent(new Event('submit', { bubbles: true, cancelable: true }));
            });
        });

        var resetBtn = document.getElementById(_cfg.formId + '-reset');
        if (resetBtn) {
            resetBtn.addEventListener('click', function () {
                form.reset();
                _currentParams = {};
                _fetchAndRender({}, 1, false);
                _updateUrl({}, 1);
            });
        }
    }

    /* ── bind grid/list view toggle ── */
    /* Uses [data-view="grid"/"list"] so multiple instances (mobile + desktop) sync */
    function _bindViewToggle() {
        var gridBtns = document.querySelectorAll('[data-view="grid"]');
        var listBtns = document.querySelectorAll('[data-view="list"]');
        var gridEl   = document.getElementById(_cfg.gridId);

        if (!gridEl || (!gridBtns.length && !listBtns.length)) return;

        gridBtns.forEach(function (btn) {
            btn.addEventListener('click', function () {
                gridBtns.forEach(function (b) { b.classList.add('active'); });
                listBtns.forEach(function (b) { b.classList.remove('active'); });
                gridEl.classList.remove('prop-listing--list');
                gridEl.querySelectorAll('.prop-listing__item').forEach(function (col) {
                    col.className = 'prop-listing__item col-6 col-md-4 col-xl-3';
                });
            });
        });

        listBtns.forEach(function (btn) {
            btn.addEventListener('click', function () {
                listBtns.forEach(function (b) { b.classList.add('active'); });
                gridBtns.forEach(function (b) { b.classList.remove('active'); });
                gridEl.classList.add('prop-listing--list');
                gridEl.querySelectorAll('.prop-listing__item').forEach(function (col) {
                    col.className = 'prop-listing__item col-12';
                });
            });
        });
    }

    /* ── bind load more button ── */
    function _bindLoadMore() {
        var btn = document.getElementById(_cfg.loadMoreBtn);
        if (!btn) return;
        btn.addEventListener('click', function () {
            if (_isLoading || _currentPage >= _lastPage) return;
            _fetchAndRender(_currentParams, _currentPage + 1, true);
        });
    }

    /* ── infinite scroll via IntersectionObserver ── */
    function _bindInfiniteScroll() {
        var wrap = document.getElementById(_cfg.loadMoreWrap);
        if (!wrap || !('IntersectionObserver' in window)) return;

        var observer = new IntersectionObserver(function (entries) {
            if (entries[0].isIntersecting && !_isLoading && _currentPage < _lastPage) {
                _fetchAndRender(_currentParams, _currentPage + 1, true);
            }
        }, { rootMargin: '300px' });

        observer.observe(wrap);
    }

    /* ── extract form values as API params ── */
    function _formToParams(form) {
        var data  = new FormData(form);
        var params = {};
        for (var pair of data.entries()) {
            if (pair[1] !== '') params[pair[0]] = pair[1];
        }
        return params;
    }

    /**
     * Fetch properties and render.
     * @param {Object}  params  - API params
     * @param {number}  page    - page number
     * @param {boolean} append  - true = append to grid (load-more), false = replace
     */
    function _fetchAndRender(params, page, append) {
        if (_isLoading) return;
        _isLoading = true;

        var gridEl  = document.getElementById(_cfg.gridId);
        var countEl = document.getElementById(_cfg.countId);

        // Show skeletons only on fresh load (not append)
        if (!append && gridEl) gridEl.innerHTML = _skeletons(6);

        _setLoadMoreState('loading');

        var reqParams = Object.assign({}, params, { page: page, per_page: 15 });

        PropertiesAjax.load(reqParams, function (data, meta) {
            _isLoading = false;
            _currentPage = meta ? meta.current_page : 1;
            _lastPage    = meta ? meta.last_page    : 1;

            _renderGrid(gridEl, data, append);
            _renderCount(countEl, meta);
            _renderLoadMore();
            _renderChips(params);
        });
    }

    /* ── render card grid ── */
    function _renderGrid(gridEl, data, append) {
        if (!gridEl) return;

        if (!append) gridEl.innerHTML = '';

        if (!data || !data.length) {
            if (!append) {
                gridEl.innerHTML = '<div class="col-12 text-center py-5 text-muted">'
                    + '<i class="bi bi-search d-block mb-3" style="font-size:3rem;opacity:.3"></i>'
                    + '<div>' + (cfg('no_results') || 'No properties found') + '</div></div>';
            }
            return;
        }

        var isListView = gridEl.classList.contains('prop-listing--list');

        data.forEach(function (p) {
            var col = document.createElement('div');
            col.className = 'prop-listing__item ' + (isListView ? 'col-12' : 'col-6 col-md-4 col-xl-3');
            col.innerHTML = PropertiesAjax.cardHtml(p);
            gridEl.appendChild(col);
        });
    }

    /* ── count label ── */
    function _renderCount(countEl, meta) {
        if (!countEl) return;
        countEl.textContent = meta ? meta.total.toLocaleString() : '—';
    }

    /* ── load more button state ── */
    function _renderLoadMore() {
        var wrap = document.getElementById(_cfg.loadMoreWrap);
        var btn  = document.getElementById(_cfg.loadMoreBtn);
        if (!wrap || !btn) return;

        if (_currentPage >= _lastPage) {
            wrap.classList.add('d-none');
        } else {
            wrap.classList.remove('d-none');
            _setLoadMoreState('idle');
        }
    }

    function _setLoadMoreState(state) {
        var btn = document.getElementById(_cfg.loadMoreBtn);
        if (!btn) return;
        if (state === 'loading') {
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>' + escHtml(cfg('loading'));
        } else {
            btn.disabled = false;
            btn.textContent = cfg('load_more') || 'Load More';
        }
    }

    /* ── active filter chips ── */
    function _renderChips(params) {
        var chipsEl = document.getElementById(_cfg.chipsId);
        if (!chipsEl) return;
        chipsEl.innerHTML = '';

        var excluded = ['sort_by', 'page', 'per_page'];
        Object.entries(params).forEach(function ([k, v]) {
            if (!v || excluded.includes(k)) return;
            var chip = document.createElement('span');
            chip.className = 'prop-chip';
            chip.textContent = escHtml(k.replace(/_id$/, '').replace(/_/g, ' ')) + ': ' + escHtml(String(v));
            var close = document.createElement('button');
            close.className = 'prop-chip__close ms-1';
            close.innerHTML = '&times;';
            close.title     = cfg('clear');
            close.addEventListener('click', function () {
                delete _currentParams[k];
                var form = document.getElementById(_cfg.formId);
                if (form) {
                    var el = form.elements[k];
                    if (el) {
                        if (el.type === 'radio' || el.type === 'checkbox') {
                            form.querySelectorAll('[name="' + k + '"]').forEach(function (r) {
                                if (r.value === '') r.checked = true;
                                else r.checked = false;
                            });
                        } else {
                            el.value = '';
                        }
                    }
                }
                _fetchAndRender(_currentParams, 1, false);
                _updateUrl(_currentParams, 1);
            });
            chip.appendChild(close);
            chipsEl.appendChild(chip);
        });
    }

    /* ── update browser URL without reload ── */
    function _updateUrl(params, page) {
        var qs = new URLSearchParams();
        Object.entries(params).forEach(function ([k, v]) {
            if (v !== null && v !== undefined && v !== '') qs.set(k, v);
        });
        if (page && page > 1) qs.set('page', page);
        var newUrl = window.location.pathname + (qs.toString() ? '?' + qs.toString() : '');
        history.replaceState(null, '', newUrl);
    }

    /* ── skeleton placeholders ── */
    function _skeletons(count) {
        var html = '';
        for (var i = 0; i < count; i++) {
            html += '<div class="col-6 col-md-4 col-xl-3">'
                + '<div class="prop-card">'
                + '<div class="prop-skeleton" style="padding-top:62%"></div>'
                + '<div class="prop-card__body">'
                + '<div class="prop-skeleton mb-2" style="height:1.1rem;width:40%"></div>'
                + '<div class="prop-skeleton mb-2" style="height:.85rem;width:75%"></div>'
                + '<div class="prop-skeleton" style="height:.75rem;width:55%"></div>'
                + '</div></div></div>';
        }
        return html;
    }

    return { init: init };

}());
