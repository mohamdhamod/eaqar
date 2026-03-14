@extends('layout.home.main')

@section('page_title', __('translation.agencies.page_title') . ' — ' . config('app.name'))

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
