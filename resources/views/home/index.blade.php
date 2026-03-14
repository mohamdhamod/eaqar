@extends('layout.home.main')
@include('layout.extra_meta')

@section('page_title', __('translation.properties.page_title') . ' — ' . __('translation.app.name'))

@section('content')

{{-- ================================================
     HERO SEARCH
     ================================================ --}}
<section class="prop-hero">
    <div class="prop-hero__overlay"></div>
    <div class="container position-relative z-1 py-5">
        <div class="row justify-content-center text-center text-white">
            <div class="col-lg-8">
                <h1 class="prop-hero__title fw-bold mb-2">
                    {{ __('translation.properties.hero_title') }}
                </h1>
                <p class="prop-hero__subtitle mb-4 opacity-90">
                    {{ __('translation.properties.hero_subtitle') }}
                </p>
            </div>
        </div>

        {{-- Quick Search Bar --}}
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="prop-hero__search-box card border-0 shadow-lg">
                    <div class="card-body p-3">
                        <form id="hero-search-form" class="row g-2 align-items-end">
                            {{-- Operation Type tabs --}}
                            <div class="col-12">
                                <div class="prop-hero__op-tabs d-flex gap-2 mb-2">
                                    <button type="button"
                                            class="prop-hero__op-tab btn btn-sm btn-primary active"
                                            data-value="">
                                        {{ __('translation.common.all') }}
                                    </button>
                                    @foreach($filterOptions['operation_types'] as $op)
                                        <button type="button"
                                                class="prop-hero__op-tab btn btn-sm btn-outline-primary"
                                                data-value="{{ $op->id }}">
                                            {{ $op->name }}
                                        </button>
                                    @endforeach
                                    <input type="hidden" name="operation_type_id" id="hero-op-id" value="">
                                </div>
                            </div>

                            {{-- Text search --}}
                            <div class="col-md-5">
                                <input type="text" name="q" class="form-control"
                                       placeholder="{{ __('translation.properties.hero_search_hint') }}">
                            </div>

                            {{-- City --}}
                            <div class="col-md-3">
                                <select name="city_id" class="form-select select2">
                                    <option value="">{{ __('translation.properties.all_cities') }}</option>
                                    @foreach($filterOptions['cities'] as $city)
                                        <option value="{{ $city->id }}">{{ $city->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Property Type --}}
                            <div class="col-md-2">
                                <select name="property_type_id" class="form-select select2">
                                    <option value="">{{ __('translation.properties.all_types') }}</option>
                                    @foreach($filterOptions['property_types'] as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Submit --}}
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-search me-1"></i>
                                    {{ __('translation.properties.search_btn') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Links Row --}}
        <div class="row justify-content-center mt-3">
            <div class="col-lg-9">
                <div class="d-flex gap-2 justify-content-center flex-wrap">
                    <a href="{{ route('properties.index') }}" class="btn btn-sm btn-outline-light text-white hero-link-btn">
                        <i class="bi bi-building me-2"></i>
                        {{ __('translation.properties.index_title') }}
                    </a>
                    <a href="{{ route('public.agencies.index') }}" class="btn btn-sm btn-outline-light text-white hero-link-btn">
                        <i class="bi bi-shop me-2"></i>
                        {{ __('translation.agencies.page_title') }}
                    </a>
                    @auth
                    
                        @if(auth()->user()->hasAnyRole(['Admin', 'Agent']))
                            <a href="{{ route('agency.index') }}" class="btn btn-sm btn-outline-light text-white hero-link-btn">
                                <i class="bi bi-house-heart me-2"></i>
                                {{ __('translation.agency.my_agency_dashboard') }}
                            </a>
                        @else
                            <a href="{{ route('agency.show') }}" class="btn btn-sm btn-outline-light text-white hero-link-btn">
                                <i class="bi bi-plus-circle me-2"></i>
                                {{ __('translation.agency.become_agent') }}
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-sm btn-outline-light text-white hero-link-btn">
                            <i class="bi bi-plus-circle me-2"></i>
                            {{ __('translation.agency.join_us') }}
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <style>
            .hero-link-btn {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                position: relative;
                overflow: hidden;
                border: 2px solid rgba(255, 255, 255, 0.6) !important;
                border-radius: 8px;
            }

            .hero-link-btn::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: rgba(255, 255, 255, 0.15);
                transition: left 0.3s ease;
                z-index: -1;
            }

            .hero-link-btn:hover::before {
                left: 0;
            }

            .hero-link-btn:hover {
                background-color: rgba(255, 255, 255, 0.2);
                border-color: rgba(255, 255, 255, 1) !important;
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25), 
                            inset 0 0 0 2px rgba(255, 255, 255, 0.3),
                            0 0 15px rgba(255, 255, 255, 0.3);
                transform: translateY(-2px);
            }

            .hero-link-btn i {
                transition: transform 0.3s ease;
            }

            .hero-link-btn:hover i {
                transform: scale(1.15);
            }
        </style>

        {{-- Stats Row --}}
        <div class="row justify-content-center mt-4">
            <div class="col-auto">
                <div class="d-flex gap-4 text-white">
                    <div class="text-center">
                        <div class="fw-bold fs-4" id="stats-total">—</div>
                        <div class="small opacity-75">{{ __('translation.properties.stat_total') }}</div>
                    </div>
                    <div class="text-center">
                        <div class="fw-bold fs-4">{{ $filterOptions['cities']->count() }}</div>
                        <div class="small opacity-75">{{ __('translation.properties.stat_cities') }}</div>
                    </div>
                    <div class="text-center">
                        <div class="fw-bold fs-4">{{ $filterOptions['property_types']->count() }}</div>
                        <div class="small opacity-75">{{ __('translation.properties.stat_types') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ================================================
     BROWSE BY TYPES (Horizontal Scrollable Tags)
     ================================================ --}}
<section class="py-5 bg-light">
    <div class="container">
        <div class="mb-4">
            <h2 class="h5 fw-bold mb-2">{{ __('translation.properties.browse_by_type') }}</h2>
            <p class="text-muted small mb-0">{{ __('translation.properties.browse_by_type_sub') }}</p>
        </div>
        <div class="prop-type-tags custom-scroll flex max-w-full flex-nowrap overflow-x-auto items-center gap-2">
            @foreach($filterOptions['property_types'] as $type)
                @php
                    $icon = match($type->code ?? '') {
                        'apartment'       => 'fa-building',
                        'house'           => 'fa-house',
                        'villa'           => 'fa-gopuram',
                        'land'            => 'fa-mountain',
                        'office'          => 'fa-briefcase',
                        'commercial_shop' => 'fa-shop',
                        'clinic'          => 'fa-hospital',
                        'warehouse'       => 'fa-warehouse',
                        'farm'            => 'fa-leaf',
                        default           => 'fa-building',
                    };
                @endphp
                <a href="{{ route('properties.index', ['property_type_id' => $type->id]) }}"
                   class="prop-type-tag d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill border border-1 border-secondary-subtle bg-white text-decoration-none text-body transition-all flex-shrink-0"
                   title="{{ $type->name }}">
                    <i class="fa-solid {{ $icon }} text-primary" style="font-size: 1rem"></i>
                    <span class="small fw-medium">{{ $type->name }}</span>
                </a>
            @endforeach
        </div>
    </div>
</section>



{{-- ================================================
     LATEST PROPERTIES (AJAX)
     ================================================ --}}
<section class="py-5">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h2 class="h4 fw-bold mb-1">{{ __('translation.properties.latest_title') }}</h2>
                <p class="text-muted small mb-0">{{ __('translation.properties.latest_subtitle') }}</p>
            </div>
            <a href="{{ route('properties.index') }}" class="btn btn-outline-primary btn-sm">
                {{ __('translation.properties.view_all') }}
            </a>
        </div>

        <div id="home-property-grid" class="row g-3 g-lg-4">
            <div class="col-12 text-center py-5" id="home-props-loading">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">{{ __('translation.common.loading') }}</span>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('properties.index') }}" class="btn btn-primary px-5">
                <i class="bi bi-grid me-2"></i>
                {{ __('translation.properties.browse_all') }}
            </a>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    window.PROPS_CONFIG = {
        apiUrl:        '{{ route('api.properties.index') }}',
        propertiesUrl: '{{ route('properties.index') }}',
        locale:        '{{ app()->getLocale() }}',
        labels: {
            loading:    '{{ __('translation.common.loading') }}',
            no_results: '{{ __('translation.properties.no_properties') }}',
            rooms:      '{{ __('translation.properties.rooms') }}',
            bathrooms:  '{{ __('translation.properties.bathrooms') }}',
            m2:         '{{ __('translation.properties.m2') }}',
            floor:      '{{ __('translation.properties.floor') }}',
            featured:   '{{ __('translation.properties.featured') }}',
            sale:       '{{ __('translation.properties.badge_sale') }}',
            rent:       '{{ __('translation.properties.badge_rent') }}',
        }
    };
</script>
@vite(['resources/js/properties.js'])
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Hero op-type tabs
    document.querySelectorAll('.prop-hero__op-tab').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.prop-hero__op-tab').forEach(function (b) {
                b.classList.remove('active', 'btn-primary');
                b.classList.add('btn-outline-primary');
            });
            this.classList.remove('btn-outline-primary');
            this.classList.add('active', 'btn-primary');
            document.getElementById('hero-op-id').value = this.dataset.value;
        });
    });

    // Hero search → redirect to properties listing
    document.getElementById('hero-search-form').addEventListener('submit', function (e) {
        e.preventDefault();
        const params = new URLSearchParams(new FormData(this));
        for (const [k, v] of [...params]) { if (!v) params.delete(k); }
        window.location.href = window.PROPS_CONFIG.propertiesUrl +
            (params.toString() ? '?' + params.toString() : '');
    });

    // Load latest properties homepage grid
    if (typeof PropertiesAjax !== 'undefined') {
        PropertiesAjax.load({ per_page: 8, sort_by: 'latest' }, function (data, meta) {
            const grid    = document.getElementById('home-property-grid');
            const loading = document.getElementById('home-props-loading');
            if (loading) loading.remove();
            if (!data.length) {
                grid.innerHTML = '<div class="col-12 text-center text-muted py-5">' +
                    window.PROPS_CONFIG.labels.no_results + '</div>';
                return;
            }
            grid.innerHTML = '';
            data.forEach(function (p) {
                const col = document.createElement('div');
                col.className = 'col-6 col-lg-3';
                col.innerHTML = PropertiesAjax.cardHtml(p);
                grid.appendChild(col);
            });
            const statEl = document.getElementById('stats-total');
            if (statEl && meta) statEl.textContent = meta.total.toLocaleString();
        });
    }
});
</script>
@endpush
