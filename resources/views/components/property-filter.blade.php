@props([
    'cities'         => collect(),
    'operationTypes' => collect(),
    'propertyTypes'  => collect(),
    'agencies'       => collect(),
    'activeFilters'  => null,
    'formId'         => 'prop-filter-form',
])
@php
    $f = $activeFilters;
@endphp

<div class="prop-filterbar">
    <div class="container">

        {{-- Toprow: filter trigger (mobile) + view toggle (always visible on mobile) --}}
        <div class="prop-filterbar__toprow d-lg-none">
            <button type="button" class="prop-filterbar__filter-btn" id="filterbar-toggle"
                    aria-expanded="false">
                <i class="bi bi-funnel-fill"></i>
                <span>{{ __('translation.properties.filter_title') }}</span>
                <i class="prop-filterbar__toggle-icon bi bi-chevron-down" id="filterbar-toggle-icon"></i>
            </button>
            <div class="prop-filterbar__toprow-end">
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-outline-secondary active"
                            data-view="grid" title="{{ __('translation.properties.grid_view') }}">
                        <i class="bi bi-grid-3x3-gap"></i>
                    </button>
                    <button type="button" class="btn btn-outline-secondary"
                            data-view="list" title="{{ __('translation.properties.list_view') }}">
                        <i class="bi bi-list-ul"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- Main filter row (form) --}}
        <form id="{{ $formId }}" class="prop-filterbar__main" novalidate autocomplete="off">

            {{-- Operation Type tabs --}}
            @if($operationTypes->isNotEmpty())
            <div class="prop-filterbar__op-tabs">
                <label class="prop-filterbar__op-tab">
                    <input type="radio" name="operation_type_id" value=""
                           {{ !$f?->operationTypeId ? 'checked' : '' }}>
                    <span>{{ __('translation.common.all') }}</span>
                </label>
                @foreach($operationTypes as $op)
                <label class="prop-filterbar__op-tab">
                    <input type="radio" name="operation_type_id" value="{{ $op->id }}"
                           {{ $f?->operationTypeId === $op->id ? 'checked' : '' }}>
                    <span>{{ $op->name }}</span>
                </label>
                @endforeach
            </div>
            <div class="prop-filterbar__divider d-none d-lg-block"></div>
            @endif

            {{-- Search --}}
            <div class="prop-filterbar__search">
                <i class="bi bi-search prop-filterbar__search-icon"></i>
                <input type="text" name="q"
                       class="prop-filterbar__search-input"
                       placeholder="{{ __('translation.properties.search_placeholder') }}"
                       value="{{ $f?->query ?? '' }}">
            </div>

            {{-- City --}}
            @if($cities->isNotEmpty())
            <div class="prop-filterbar__select-wrap">
                <select name="city_id" class="prop-filterbar__select select2">
                    <option value="">{{ __('translation.properties.city') }}</option>
                    @foreach($cities as $city)
                    <option value="{{ $city->id }}" {{ $f?->cityId === $city->id ? 'selected' : '' }}>
                        {{ $city->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            @endif

            {{-- Property Type --}}
            @if($propertyTypes->isNotEmpty())
            <div class="prop-filterbar__select-wrap">
                <select name="property_type_id" class="prop-filterbar__select select2">
                    <option value="">{{ __('translation.properties.property_type') }}</option>
                    @foreach($propertyTypes as $type)
                    <option value="{{ $type->id }}" {{ $f?->propertyTypeId === $type->id ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            @endif

            {{-- Agencies --}}
            @if($agencies->isNotEmpty())
            <div class="prop-filterbar__select-wrap">
                <select name="agency_id" class="prop-filterbar__select select2">
                    <option value="">{{ __('translation.properties.agency') }}</option>
                    @foreach($agencies as $agency)
                    <option value="{{ $agency->id }}" {{ $f?->agencyId === $agency->id ? 'selected' : '' }}>
                        {{ $agency->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            @endif

            {{-- More filters --}}
            <button type="button" class="prop-filterbar__more-btn"
                    data-bs-toggle="collapse" data-bs-target="#pf-more-panel"
                    aria-expanded="false" aria-controls="pf-more-panel">
                <i class="bi bi-sliders2"></i>
                <span class="d-none d-xl-inline">{{ __('translation.properties.more_filters') }}</span>
            </button>

            {{-- View toggle (desktop: syncs with mobile toprow toggle via [data-view]) --}}
            <div class="btn-group btn-group-sm prop-filterbar__view-toggle d-none d-lg-inline-flex" role="group">
                <button type="button" class="btn btn-outline-secondary active"
                        data-view="grid" title="{{ __('translation.properties.grid_view') }}">
                    <i class="bi bi-grid-3x3-gap"></i>
                </button>
                <button type="button" class="btn btn-outline-secondary"
                        data-view="list" title="{{ __('translation.properties.list_view') }}">
                    <i class="bi bi-list-ul"></i>
                </button>
            </div>

            {{-- Actions --}}
            <div class="prop-filterbar__actions">
                <button type="submit" class="prop-filterbar__search-btn">
                    <i class="bi bi-search"></i>
                    <span class="d-none d-lg-inline ms-1">{{ __('translation.properties.apply_filter') }}</span>
                </button>
                <button type="button" id="{{ $formId }}-reset"
                        class="prop-filterbar__reset-btn"
                        title="{{ __('translation.common.reset') }}">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

        </form>

        {{-- More filters collapsible panel --}}
        <div class="collapse" id="pf-more-panel">
            <div class="prop-filterbar__more">

                {{-- Rooms --}}
                <div class="prop-filterbar__more-group">
                    <div class="prop-filterbar__more-label">{{ __('translation.properties.rooms_min') }}</div>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach([null, 1, 2, 3, 4, 5] as $r)
                        <label class="prop-filterbar__pill">
                            <input type="radio" name="rooms" value="{{ $r ?? '' }}"
                                   {{ ($f?->rooms ?? null) === $r ? 'checked' : '' }}>
                            <span>{{ $r ? $r . '+' : __('translation.common.all') }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Area --}}
                <div class="prop-filterbar__more-group">
                    <div class="prop-filterbar__more-label">
                        {{ __('translation.properties.area_range') }}
                        <span class="fw-normal">({{ __('translation.properties.m2') }})</span>
                    </div>
                    <div class="d-flex gap-2" style="max-width:260px">
                        <input type="number" name="area_min" class="form-control form-control-sm"
                               placeholder="{{ __('translation.properties.area_min') }}"
                               value="{{ $f?->areaMin ?? '' }}" min="0">
                        <input type="number" name="area_max" class="form-control form-control-sm"
                               placeholder="{{ __('translation.properties.area_max') }}"
                               value="{{ $f?->areaMax ?? '' }}" min="0">
                    </div>
                </div>

                {{-- Price Range --}}
                <div class="prop-filterbar__more-group">
                    <div class="prop-filterbar__more-label">{{ __('translation.properties.price_range') }}</div>
                    <div class="d-flex gap-2" style="max-width:260px">
                        <input type="number" name="price_min" class="form-control form-control-sm"
                               placeholder="{{ __('translation.properties.price_min') }}"
                               value="{{ $f?->priceMin ?? '' }}" min="0">
                        <input type="number" name="price_max" class="form-control form-control-sm"
                               placeholder="{{ __('translation.properties.price_max') }}"
                               value="{{ $f?->priceMax ?? '' }}" min="0">
                    </div>
                </div>

                {{-- Sort --}}
                <div class="prop-filterbar__more-group">
                    <div class="prop-filterbar__more-label">{{ __('translation.common.sort_by') }}</div>
                    <select name="sort_by" class="form-select form-select-sm select2" style="max-width:210px">
                        <option value="latest"     {{ ($f?->sortBy ?? 'latest') === 'latest'     ? 'selected' : '' }}>{{ __('translation.properties.sort_latest') }}</option>
                        <option value="price_asc"  {{ ($f?->sortBy ?? 'latest') === 'price_asc'  ? 'selected' : '' }}>{{ __('translation.properties.sort_price_asc') }}</option>
                        <option value="price_desc" {{ ($f?->sortBy ?? 'latest') === 'price_desc' ? 'selected' : '' }}>{{ __('translation.properties.sort_price_desc') }}</option>
                        <option value="area_desc"  {{ ($f?->sortBy ?? 'latest') === 'area_desc'  ? 'selected' : '' }}>{{ __('translation.properties.sort_area_desc') }}</option>
                    </select>
                </div>

            </div>
        </div>

    </div>
</div>

<script>
(function () {
    // Price (and any future) dropdown panels
    document.querySelectorAll('[data-pf-toggle]').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            var panel = document.getElementById(btn.dataset.pfToggle);
            var opening = !panel.classList.contains('is-open');
            // Close all
            document.querySelectorAll('.prop-filterbar__dropdown-panel').forEach(function (p) { p.classList.remove('is-open'); });
            document.querySelectorAll('.prop-filterbar__dropdown-btn').forEach(function (b) { b.classList.remove('is-open'); });
            if (opening) {
                panel.classList.add('is-open');
                btn.classList.add('is-open');
            }
        });
    });

    // Close panels on outside click
    document.addEventListener('click', function () {
        document.querySelectorAll('.prop-filterbar__dropdown-panel').forEach(function (p) { p.classList.remove('is-open'); });
        document.querySelectorAll('.prop-filterbar__dropdown-btn').forEach(function (b) { b.classList.remove('is-open'); });
    });

    // Mobile toggle
    var toggle = document.getElementById('filterbar-toggle');
    if (toggle) {
        toggle.addEventListener('click', function () {
            var main = document.querySelector('.prop-filterbar__main');
            var expanded = main.classList.toggle('is-open');
            toggle.setAttribute('aria-expanded', expanded ? 'true' : 'false');
            document.getElementById('filterbar-toggle-icon').className =
                'prop-filterbar__toggle-icon bi ' + (expanded ? 'bi-chevron-up' : 'bi-chevron-down');
        });
    }
}());
</script>
