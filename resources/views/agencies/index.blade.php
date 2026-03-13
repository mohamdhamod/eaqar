@extends('layout.home.main')

@section('page_title', __('translation.agencies.page_title') . ' — ' . config('app.name'))

@push('styles')
<style>
/* =====================================================
   AGENCIES LISTING — Bayut-style
   ===================================================== */

/* ── Hero ── */
.ag-hero {
    position: relative;
    background: linear-gradient(135deg, #0f2027 0%, #203a43 40%, #2c5364 100%);
    padding: 2.8rem 0 2.2rem;
    overflow: hidden;
}
.ag-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='.03'%3E%3Cpath d='M0 20h40v1H0zM20 0v40h1V0z'/%3E%3C/g%3E%3C/svg%3E");
    pointer-events: none;
}
.ag-hero__title {
    font-size: clamp(1.55rem, 3.5vw, 2.2rem);
    font-weight: 800;
    letter-spacing: -.4px;
    color: #fff;
}
.ag-hero__sub {
    color: rgba(255,255,255,.7);
    font-size: .9rem;
    margin-top: .35rem;
}
.ag-hero__breadcrumb { font-size: .8rem; }
.ag-hero__breadcrumb a { color: rgba(255,255,255,.6); text-decoration: none; }
.ag-hero__breadcrumb a:hover { color: #fff; }
.ag-hero__breadcrumb .active { color: rgba(255,255,255,.45); }

/* ── Search bar ── */
.ag-search-bar {
    background: #fff;
    border-bottom: 1px solid #e5e9f2;
    padding: .85rem 0;
    position: sticky;
    top: 60px;
    z-index: 10;
}
.ag-search-wrap {
    display: flex;
    align-items: center;
    gap: .6rem;
    background: #f4f6fb;
    border: 1.5px solid #e0e7f1;
    border-radius: 12px;
    padding: .5rem .85rem;
    max-width: 540px;
    transition: border-color .2s, box-shadow .2s;
}
.ag-search-wrap:focus-within {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37,99,235,.1);
    background: #fff;
}
.ag-search-wrap i.bi-search { color: #94a3b8; font-size: .95rem; flex-shrink: 0; }
.ag-search__input {
    border: none;
    background: transparent;
    flex: 1;
    font-size: .88rem;
    color: #1e293b;
    min-width: 0;
}
.ag-search__input::placeholder { color: #94a3b8; }
.ag-search__input:focus { outline: none; }
.ag-search__clear {
    border: none;
    background: #fee2e2;
    color: #dc2626;
    border-radius: 6px;
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: .8rem;
    flex-shrink: 0;
    transition: background .15s;
}
.ag-search__clear:hover { background: #fecaca; }
.ag-search__btn {
    border: none;
    background: #2563eb;
    color: #fff;
    border-radius: 8px;
    padding: .4rem .95rem;
    font-size: .82rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: .35rem;
    cursor: pointer;
    flex-shrink: 0;
    transition: background .15s;
}
.ag-search__btn:hover { background: #1d4ed8; }
.ag-search__count {
    font-size: .8rem;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: .35rem;
    white-space: nowrap;
}
.ag-search__count strong { color: #1e293b; font-weight: 700; }

/* ── Grid Section ── */
.ag-grid-section { padding: 1.5rem 0 3rem; background: #f8fafc; min-height: 60vh; }

/* ── Card ── */
.agency-card {
    background: #fff;
    border: 1px solid #e8edf5;
    border-radius: 16px;
    overflow: hidden;
    transition: box-shadow .2s, transform .2s, border-color .2s;
    height: 100%;
    display: flex;
    flex-direction: column;
}
.agency-card:hover {
    box-shadow: 0 8px 30px rgba(37,99,235,.1);
    transform: translateY(-3px);
    border-color: #c7d9f7;
}

.agency-card__banner {
    height: 72px;
    background: linear-gradient(135deg, #e0eaff 0%, #dbeafe 100%);
    position: relative;
    flex-shrink: 0;
}
.agency-card__logo-wrap {
    position: absolute;
    bottom: -26px;
    left: 50%;
    transform: translateX(-50%);
}
.agency-card__logo {
    width: 56px; height: 56px;
    border-radius: 12px;
    border: 3px solid #fff;
    object-fit: cover;
    box-shadow: 0 3px 12px rgba(0,0,0,.1);
    background: #f0f6ff;
}
.agency-card__logo-placeholder {
    width: 56px; height: 56px;
    border-radius: 12px;
    border: 3px solid #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.35rem;
    font-weight: 700;
    color: #fff;
    box-shadow: 0 3px 12px rgba(0,0,0,.1);
}
.agency-card__body {
    padding: 2rem 1rem 1rem;
    flex: 1;
    display: flex;
    flex-direction: column;
    text-align: center;
}
.agency-card__name {
    font-size: .92rem;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: .2rem;
    line-height: 1.35;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.agency-card__address {
    font-size: .72rem;
    color: #64748b;
    margin-bottom: .6rem;
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.agency-card__address i { font-size: .65rem; }
.agency-card__props-badge {
    display: inline-flex;
    align-items: center;
    gap: .35rem;
    background: #eff6ff;
    color: #2563eb;
    border-radius: 20px;
    padding: .25rem .7rem;
    font-size: .72rem;
    font-weight: 600;
    margin: 0 auto .65rem;
}
.agency-card__meta {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: .35rem;
    margin-bottom: .65rem;
}
.agency-card__meta-item {
    display: inline-flex;
    align-items: center;
    gap: .25rem;
    font-size: .68rem;
    color: #7a8aa4;
    background: #f7f9fc;
    border-radius: 6px;
    padding: .18rem .5rem;
    max-width: 130px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.agency-card__meta-item i { color: #9baec8; font-size: .7rem; flex-shrink: 0; }
.agency-card__meta-item a { color: inherit; text-decoration: none; }
.agency-card__meta-item a:hover { color: #2563eb; }

.agency-card__footer {
    padding: .7rem 1rem;
    border-top: 1px solid #f0f4fa;
    flex-shrink: 0;
}
.agency-card__cta {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .4rem;
    background: #2563eb;
    color: #fff;
    border-radius: 8px;
    font-weight: 600;
    font-size: .78rem;
    padding: .5rem .8rem;
    text-decoration: none;
    transition: background .15s;
}
.agency-card__cta:hover { background: #1d4ed8; color: #fff; }
.agency-card__cta i { font-size: .75rem; }
.agency-card__cta--edit {
    background: transparent;
    color: #2563eb;
    border: 1.5px solid #2563eb;
}
.agency-card__cta--edit:hover { background: #2563eb; color: #fff; }

/* ── Empty State ── */
.agencies-empty {
    padding: 3.5rem 0;
    text-align: center;
}
.agencies-empty__icon {
    width: 80px; height: 80px;
    border-radius: 50%;
    background: #eff6ff;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.3rem;
    font-size: 2rem;
    color: #93c5fd;
}

/* ── Skeleton ── */
.agency-skeleton {
    background: linear-gradient(90deg, #e8edf5 25%, #f3f6fa 50%, #e8edf5 75%);
    background-size: 200% 100%;
    animation: agSkeletonShimmer 1.5s infinite;
    border-radius: 6px;
}
.agency-skeleton-circle {
    width: 56px; height: 56px;
    border-radius: 12px;
    border: 3px solid #fff;
    background: linear-gradient(90deg, #e0e7f0 25%, #edf1f7 50%, #e0e7f0 75%);
    background-size: 200% 100%;
    animation: agSkeletonShimmer 1.5s infinite;
}
@keyframes agSkeletonShimmer {
    0%   { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

/* ── Load More ── */
.ag-load-more { padding: .5rem 0 2rem; text-align: center; }
.ag-load-more__btn {
    border: 1.5px solid #d1d9e6;
    background: #fff;
    color: #2563eb;
    border-radius: 10px;
    padding: .55rem 2rem;
    font-weight: 600;
    font-size: .85rem;
    cursor: pointer;
    transition: background .15s, border-color .15s;
}
.ag-load-more__btn:hover { background: #eff6ff; border-color: #2563eb; }
.ag-load-more__btn:disabled { opacity: .6; cursor: wait; }

/* ── RTL ── */
[dir="rtl"] .agency-card__logo-wrap { left: auto; right: 50%; transform: translateX(50%); }
[dir="rtl"] .agency-card__cta { flex-direction: row-reverse; }
[dir="rtl"] .ag-search__count { direction: rtl; }

/* ── Responsive ── */
@media (max-width: 575.98px) {
    .ag-hero { padding: 1.8rem 0 1.4rem; }
    .ag-hero__title { font-size: 1.3rem; }
    .agency-card__banner { height: 56px; }
    .agency-card__logo, .agency-card__logo-placeholder,
    .agency-skeleton-circle { width: 46px; height: 46px; border-radius: 10px; }
    .agency-card__logo-wrap { bottom: -22px; }
    .agency-card__body { padding-top: 1.6rem; padding-left: .7rem; padding-right: .7rem; }
    .agency-card__name { font-size: .82rem; }
    .agency-card__meta-item { font-size: .62rem; }
    .agency-card__cta { font-size: .72rem; padding: .4rem .6rem; }
}
</style>
@endpush

@section('content')

{{-- ═══════════════════════════════════════════════
     HERO
     ═══════════════════════════════════════════════ --}}
<section class="ag-hero">
    <div class="container position-relative z-1">
        <nav class="ag-hero__breadcrumb mb-2">
            <a href="{{ route('home') }}">{{ __('translation.layout.home.nav_home') }}</a>
            <span class="mx-1 text-white-50">/</span>
            <span class="active">{{ __('translation.agencies.page_title') }}</span>
        </nav>
        <h1 class="ag-hero__title mb-0">{{ __('translation.agencies.page_title') }}</h1>
        <p class="ag-hero__sub mb-0">{{ __('translation.agencies.page_subtitle') }}</p>
    </div>
</section>

{{-- ═══════════════════════════════════════════════
     SEARCH BAR (sticky)
     ═══════════════════════════════════════════════ --}}
<div class="ag-search-bar">
    <div class="container d-flex align-items-center justify-content-between gap-3 flex-wrap">
        <form id="ag-search-form" class="ag-search-wrap" autocomplete="off">
            <i class="bi bi-search"></i>
            <input
                type="text"
                id="ag-search-input"
                class="ag-search__input"
                placeholder="{{ __('translation.agencies.search_placeholder') }}"
            >
            <button type="button" id="ag-clear-btn" class="ag-search__clear" style="display:none" title="{{ __('translation.agencies.clear_search') }}">
                <i class="bi bi-x-lg"></i>
            </button>
            <button type="submit" class="ag-search__btn">
                <i class="bi bi-search"></i>
                {{ __('translation.common.search') }}
            </button>
        </form>

        <div class="ag-search__count">
            <i class="bi bi-building"></i>
            <span><strong id="ag-results-count">—</strong> {{ __('translation.agencies.results_found') }}</span>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════
     GRID
     ═══════════════════════════════════════════════ --}}
<section class="ag-grid-section">
    <div class="container">
        <div id="ag-grid" class="row g-3"></div>

        <div id="ag-load-more" class="ag-load-more d-none">
            <button type="button" id="ag-load-more-btn" class="ag-load-more__btn">
                {{ __('translation.agencies.load_more') }}
            </button>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    window.AGENCIES_CONFIG = {
        apiUrl:        '{{ route('api.agencies.index') }}',
        locale:        '{{ app()->getLocale() }}',
        currentUserId: {{ auth()->id() ?? 'null' }},
        editUrl:       '{{ auth()->check() ? route('agency.show') : '' }}',
        labels: {
            loading:         '{{ __('translation.agencies.loading') }}',
            load_more:       '{{ __('translation.agencies.load_more') }}',
            no_agencies:     '{{ __('translation.agencies.no_agencies') }}',
            no_agencies_hint:'{{ __('translation.agencies.no_agencies_hint') }}',
            properties:      '{{ __('translation.agencies.properties') }}',
            view_properties: '{{ __('translation.agencies.view_properties') }}',
            edit_agency:     '{{ __('translation.agencies.edit_agency') }}',
            results_found:   '{{ __('translation.agencies.results_found') }}',
        },
    };
</script>
@vite(['resources/js/agencies.js'])
<script>
document.addEventListener('DOMContentLoaded', function () {
    AgenciesListing.init({
        searchForm:   'ag-search-form',
        searchInput:  'ag-search-input',
        clearBtn:     'ag-clear-btn',
        gridId:       'ag-grid',
        countId:      'ag-results-count',
        loadMoreWrap: 'ag-load-more',
        loadMoreBtn:  'ag-load-more-btn',
    });
});
</script>
@endpush
