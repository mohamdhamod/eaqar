/**
 * agencies.js — AJAX agencies listing module (Bayut-style)
 * Pattern: same as properties.js
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

function lbl(key) {
    return (window.AGENCIES_CONFIG && window.AGENCIES_CONFIG.labels)
        ? (window.AGENCIES_CONFIG.labels[key] || '')
        : '';
}

/* ================================================================
   AgenciesAjax — low-level fetch + card renderer
   ================================================================ */
window.AgenciesAjax = (function () {

    function load(params, callback) {
        var apiUrl = (window.AGENCIES_CONFIG && window.AGENCIES_CONFIG.apiUrl) || '/api/agencies';
        var qs = new URLSearchParams();

        Object.entries(params || {}).forEach(function ([k, v]) {
            if (v !== null && v !== undefined && v !== '') {
                qs.set(k, v);
            }
        });

        var url = apiUrl + (qs.toString() ? '?' + qs.toString() : '');

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
            callback(json.data || [], json.meta || null, json.links || null);
        })
        .catch(function (err) {
            console.error('[AgenciesAjax] fetch error:', err);
            callback([], null, null);
        });
    }

    /**
     * Generate a gradient color based on the agency name for the placeholder
     */
    function nameColor(name) {
        var hash = 0;
        for (var i = 0; i < (name || '').length; i++) {
            hash = name.charCodeAt(i) + ((hash << 5) - hash);
        }
        var colors = [
            ['#2563eb','#0ea5e9'], ['#7c3aed','#a855f7'], ['#059669','#10b981'],
            ['#d97706','#f59e0b'], ['#dc2626','#ef4444'], ['#0891b2','#06b6d4'],
            ['#4f46e5','#818cf8'], ['#be185d','#ec4899'],
        ];
        var pair = colors[Math.abs(hash) % colors.length];
        return 'linear-gradient(135deg,' + pair[0] + ',' + pair[1] + ')';
    }

    function cardHtml(a) {
        var name     = escHtml(a.name || '');
        var address  = escHtml(a.address || '');
        var phone    = escHtml(a.phone || '');
        var email    = escHtml(a.email || '');
        var website  = a.website || '';
        var logo     = a.logo || '';
        var count    = a.properties_count || 0;
        var url      = escHtml(a.properties_url || '#');
        var initials = (a.name || '?').charAt(0).toUpperCase();
        var gradient = nameColor(a.name);
        var cfg      = window.AGENCIES_CONFIG || {};
        var isOwner  = cfg.currentUserId && a.user_id == cfg.currentUserId;
        var editUrl  = cfg.editUrl || '';

        // Website display
        var websiteHost = '';
        if (website) {
            try { websiteHost = new URL(website).hostname; } catch(e) { websiteHost = website; }
        }

        // Logo or placeholder
        var logoHtml = logo
            ? '<img src="' + escHtml(logo) + '" alt="' + name + '" class="agency-card__logo">'
            : '<div class="agency-card__logo-placeholder" style="background:' + gradient + '">' + escHtml(initials) + '</div>';

        // Meta chips
        var metaHtml = '';
        if (phone) {
            metaHtml += '<span class="agency-card__meta-item"><i class="bi bi-telephone"></i><a href="tel:' + phone + '">' + phone + '</a></span>';
        }
        if (email) {
            metaHtml += '<span class="agency-card__meta-item"><i class="bi bi-envelope"></i><a href="mailto:' + email + '">' + email + '</a></span>';
        }
        if (website) {
            metaHtml += '<span class="agency-card__meta-item"><i class="bi bi-globe2"></i><a href="' + escHtml(website) + '" target="_blank" rel="noopener noreferrer">' + escHtml(websiteHost) + '</a></span>';
        }

        return '<div class="agency-card">'
            + '<div class="agency-card__banner">'
            +   '<div class="agency-card__logo-wrap">' + logoHtml + '</div>'
            + '</div>'
            + '<div class="agency-card__body">'
            +   '<h3 class="agency-card__name">' + name + '</h3>'
            +   (address ? '<p class="agency-card__address"><i class="bi bi-geo-alt"></i> ' + address + '</p>' : '')
            +   '<span class="agency-card__props-badge"><i class="bi bi-houses"></i> ' + count + ' ' + escHtml(lbl('properties')) + '</span>'
            +   (metaHtml ? '<div class="agency-card__meta">' + metaHtml + '</div>' : '')
            + '</div>'
            + '<div class="agency-card__footer">'
            +   '<a href="' + url + '" class="agency-card__cta"><i class="bi bi-grid-3x3-gap"></i> ' + escHtml(lbl('view_properties')) + '</a>'
            + '</div>'
            + '</div>';
    }

    return { load: load, cardHtml: cardHtml };
}());

/* ================================================================
   AgenciesListing — full listing-page controller
   ================================================================ */
window.AgenciesListing = (function () {

    var _cfg = {};
    var _currentParams = {};
    var _currentPage = 1;
    var _lastPage = 1;
    var _totalCount = 0;
    var _isLoading = false;
    var _debounceTimer = null;

    function init(options) {
        _cfg = options;

        // Read initial search from URL
        var urlParams = new URLSearchParams(window.location.search);
        var initialSearch = urlParams.get('search') || '';
        if (initialSearch) {
            _currentParams.search = initialSearch;
            var input = document.getElementById(_cfg.searchInput);
            if (input) input.value = initialSearch;
        }

        _bindSearch();
        _bindLoadMore();
        _bindInfiniteScroll();
        _fetchAndRender(_currentParams, 1, false);
    }

    /* ── bind search ── */
    function _bindSearch() {
        var form  = document.getElementById(_cfg.searchForm);
        var input = document.getElementById(_cfg.searchInput);
        var clearBtn = document.getElementById(_cfg.clearBtn);

        if (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                _doSearch();
            });
        }

        // Live search with debounce
        if (input) {
            input.addEventListener('input', function () {
                clearTimeout(_debounceTimer);
                _debounceTimer = setTimeout(_doSearch, 400);
                _toggleClearBtn();
            });
        }

        if (clearBtn) {
            clearBtn.addEventListener('click', function () {
                if (input) input.value = '';
                _currentParams = {};
                _toggleClearBtn();
                _fetchAndRender({}, 1, false);
                _updateUrl({});
            });
        }
    }

    function _doSearch() {
        var input = document.getElementById(_cfg.searchInput);
        var val   = input ? input.value.trim() : '';
        _currentParams = val ? { search: val } : {};
        _toggleClearBtn();
        _fetchAndRender(_currentParams, 1, false);
        _updateUrl(_currentParams);
    }

    function _toggleClearBtn() {
        var input    = document.getElementById(_cfg.searchInput);
        var clearBtn = document.getElementById(_cfg.clearBtn);
        if (clearBtn && input) {
            clearBtn.style.display = input.value.trim() ? '' : 'none';
        }
    }

    /* ── bind load more ── */
    function _bindLoadMore() {
        var btn = document.getElementById(_cfg.loadMoreBtn);
        if (!btn) return;
        btn.addEventListener('click', function () {
            if (_isLoading || _currentPage >= _lastPage) return;
            _fetchAndRender(_currentParams, _currentPage + 1, true);
        });
    }

    /* ── infinite scroll ── */
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

    /**
     * Fetch agencies and render.
     */
    function _fetchAndRender(params, page, append) {
        if (_isLoading) return;
        _isLoading = true;

        var gridEl  = document.getElementById(_cfg.gridId);
        var countEl = document.getElementById(_cfg.countId);

        if (!append && gridEl) gridEl.innerHTML = _skeletons(8);

        _setLoadMoreState('loading');

        var reqParams = Object.assign({}, params, { page: page, per_page: 20 });

        AgenciesAjax.load(reqParams, function (data, meta) {
            _isLoading   = false;
            _currentPage = meta ? meta.current_page : 1;
            _lastPage    = meta ? meta.last_page    : 1;
            _totalCount  = meta ? meta.total        : 0;

            _renderGrid(gridEl, data, append);
            _renderCount(countEl);
            _renderLoadMore();
        });
    }

    /* ── render grid ── */
    function _renderGrid(gridEl, data, append) {
        if (!gridEl) return;

        if (!append) gridEl.innerHTML = '';

        if (!data || !data.length) {
            if (!append) {
                gridEl.innerHTML = '<div class="col-12">'
                    + '<div class="agencies-empty">'
                    + '<div class="agencies-empty__icon"><i class="bi bi-building-slash"></i></div>'
                    + '<h4 class="fw-semibold text-dark mb-2">' + escHtml(lbl('no_agencies')) + '</h4>'
                    + '<p class="text-muted">' + escHtml(lbl('no_agencies_hint')) + '</p>'
                    + '</div></div>';
            }
            return;
        }

        data.forEach(function (a) {
            var col = document.createElement('div');
            col.className = 'col-6 col-md-4 col-lg-3';
            col.innerHTML = AgenciesAjax.cardHtml(a);
            gridEl.appendChild(col);
        });
    }

    /* ── count ── */
    function _renderCount(countEl) {
        if (!countEl) return;
        countEl.textContent = _totalCount.toLocaleString();
    }

    /* ── load more ── */
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
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>' + escHtml(lbl('loading'));
        } else {
            btn.disabled = false;
            btn.textContent = lbl('load_more');
        }
    }

    /* ── skeletons ── */
    function _skeletons(count) {
        var html = '';
        for (var i = 0; i < count; i++) {
            html += '<div class="col-6 col-md-4 col-lg-3">'
                + '<div class="agency-card" style="pointer-events:none">'
                + '<div class="agency-card__banner" style="background:#e8edf5">'
                + '<div class="agency-card__logo-wrap"><span class="agency-skeleton-circle"></span></div>'
                + '</div>'
                + '<div class="agency-card__body">'
                + '<div class="agency-skeleton" style="width:65%;height:14px;margin:0 auto .5rem"></div>'
                + '<div class="agency-skeleton" style="width:45%;height:10px;margin:0 auto .4rem"></div>'
                + '<div class="agency-skeleton" style="width:30%;height:22px;margin:0 auto .5rem;border-radius:20px"></div>'
                + '</div>'
                + '<div class="agency-card__footer">'
                + '<div class="agency-skeleton" style="width:100%;height:34px;border-radius:8px"></div>'
                + '</div>'
                + '</div></div>';
        }
        return html;
    }

    /* ── URL management ── */
    function _updateUrl(params) {
        var url = new URL(window.location);
        url.search = '';
        Object.entries(params || {}).forEach(function ([k, v]) {
            if (v) url.searchParams.set(k, v);
        });
        history.replaceState(null, '', url.toString());
    }

    function _readUrlParams() {
        var sp     = new URLSearchParams(window.location.search);
        var search = sp.get('search');
        if (search) _currentParams.search = search;
        return _currentParams;
    }

    /* ── public API ── */
    return { init: init };
})();
