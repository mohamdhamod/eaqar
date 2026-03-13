@extends('layout.home.main')

@section('page_title', __('translation.properties.index_title') . ' — ' . config('app.name'))

@section('content')

{{-- Page Header --}}
<div class="bg-body-tertiary border-bottom py-3">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}">{{ __('translation.common.back_to_home') }}</a>
                </li>
                <li class="breadcrumb-item active">{{ __('translation.properties.index_title') }}</li>
            </ol>
        </nav>
    </div>
</div>

{{-- Horizontal Filter Bar (sticky, above listings) --}}
<x-property-filter
    :cities="$filterOptions['cities']"
    :operation-types="$filterOptions['operation_types']"
    :property-types="$filterOptions['property_types']"
    :agencies="$filterOptions['agencies']"
    :active-filters="$activeFilters"
    form-id="prop-filter-form"
/>

<div class="container py-4">

    {{-- Toolbar --}}
    <div class="d-flex align-items-center mb-3">
        <div class="text-muted small">
            <span id="results-count">—</span>
            {{ __('translation.properties.results_found') }}
        </div>
    </div>

    {{-- Active filter chips --}}
    <div id="active-filter-chips" class="d-flex flex-wrap gap-2 mb-3"></div>

    {{-- Properties Grid --}}
    <div id="prop-listing-grid" class="row g-3">
        <div class="col-12 text-center py-5" id="listing-loading">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">{{ __('translation.common.loading') }}</span>
            </div>
        </div>
    </div>

    {{-- Load More --}}
    <div id="prop-load-more" class="prop-load-more-wrap d-none">
        <button type="button" id="prop-load-more-btn" class="btn btn-outline-primary prop-load-more-btn">
            {{ __('translation.properties.load_more') }}
        </button>
    </div>

</div>

@endsection

@push('scripts')
<script>
    window.PROPS_CONFIG = {
        apiUrl:  '{{ route('api.properties.index') }}',
        locale:  '{{ app()->getLocale() }}',
        filters: @json($activeFilters),
        labels: {
            loading:     '{{ __('translation.common.loading') }}',
            no_results:  '{{ __('translation.properties.no_properties') }}',
            rooms:       '{{ __('translation.properties.rooms') }}',
            bathrooms:   '{{ __('translation.properties.bathrooms') }}',
            m2:          '{{ __('translation.properties.m2') }}',
            floor:       '{{ __('translation.properties.floor') }}',
            featured:    '{{ __('translation.properties.featured') }}',
            sale:        '{{ __('translation.properties.badge_sale') }}',
            rent:        '{{ __('translation.properties.badge_rent') }}',
            results:     '{{ __('translation.properties.results_found') }}',
            load_more:   '{{ __('translation.properties.load_more') }}',
            active_city: '{{ __('translation.properties.city') }}',
            active_op:   '{{ __('translation.properties.operation_type') }}',
            active_type: '{{ __('translation.properties.property_type') }}',
            active_agency: '{{ __('translation.properties.agency') }}',
            clear:       '{{ __('translation.common.reset') }}',
        },
        // Maps for converting IDs to display names
        optionsMap: {
            city_id: @json($filterOptions['cities']->pluck('name', 'id')->toArray()),
            operation_type_id: @json($filterOptions['operation_types']->pluck('name', 'id')->toArray()),
            property_type_id: @json($filterOptions['property_types']->pluck('name', 'id')->toArray()),
            agency_id: @json($filterOptions['agencies']->pluck('name', 'id')->toArray()),
        }
    };
</script>
@vite(['resources/js/properties.js'])
<script>
document.addEventListener('DOMContentLoaded', function () {
    PropertiesListing.init({
        formId:       'prop-filter-form',
        gridId:       'prop-listing-grid',
        countId:      'results-count',
        loadMoreWrap: 'prop-load-more',
        loadMoreBtn:  'prop-load-more-btn',
        chipsId:      'active-filter-chips',
        viewGridBtn:  'view-grid',
        viewListBtn:  'view-list',
    });
});
</script>
@endpush
