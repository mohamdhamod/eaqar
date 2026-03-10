@extends('layout.home.main')

@section('page_title', $property->title . ' — ' . config('app.name'))

@section('extra_meta')
<meta name="description" content="{{ Str::limit(strip_tags($property->description ?? ''), 160) }}">
<meta property="og:title"       content="{{ $property->title }}">
<meta property="og:description" content="{{ Str::limit(strip_tags($property->description ?? ''), 160) }}">
@if($property->mainImage)
<meta property="og:image"       content="{{ $property->mainImage->image }}">
@endif
@endsection

@section('content')

{{-- Breadcrumb --}}
<div class="bg-body-tertiary border-bottom py-3">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}">{{ __('translation.common.back_to_home') }}</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('properties.index') }}">{{ __('translation.properties.index_title') }}</a>
                </li>
                <li class="breadcrumb-item active text-truncate" style="max-width:240px">
                    {{ $property->title }}
                </li>
            </ol>
        </nav>
    </div>
</div>

{{-- Gallery Grid (Bayut-style) --}}
@php
    $images = $property->images;
    $imgCount = $images->count();
@endphp
<div class="container py-3">
    @if($imgCount > 0)
        <div class="prop-gallery-grid {{ $imgCount === 1 ? 'prop-gallery-grid--single' : 'prop-gallery-grid--multi' }}"
             id="gallery-grid">
            @foreach($images->take(3) as $i => $img)
                <div class="prop-gallery-grid__item" data-index="{{ $i }}">
                    <img src="{{ $img->image }}"
                         alt="{{ $property->title }}"
                         loading="{{ $loop->first ? 'eager' : 'lazy' }}">
                    @if($i === 2 && $imgCount > 3)
                        <div class="prop-gallery-grid__more">
                            <i class="bi bi-images"></i>
                            <span>+{{ $imgCount - 3 }}</span>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="prop-gallery-grid prop-gallery-grid--single">
            <div class="prop-gallery-grid__item d-flex align-items-center justify-content-center bg-light" style="min-height:320px">
                <i class="bi bi-buildings text-secondary" style="font-size:5rem"></i>
            </div>
        </div>
    @endif
</div>

{{-- Fullscreen Gallery Modal --}}
@if($imgCount > 1)
<div class="modal fade prop-gallery-modal" id="galleryModal" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header border-0 position-absolute top-0 end-0" style="z-index:10">
                <span class="text-white small me-3">{{ $imgCount }} {{ __('translation.properties.photos') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body d-flex align-items-center p-0">
                <div class="swiper w-100 h-100" id="gallerySwiper">
                    <div class="swiper-wrapper">
                        @foreach($images as $img)
                            <div class="swiper-slide d-flex align-items-center justify-content-center">
                                <img src="{{ $img->image }}" alt="{{ $property->title }}" loading="lazy">
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<div class="container py-4">
    <div class="row g-4">

        {{-- ── Main Content ─────────────────────────────────── --}}
        <div class="col-lg-8">

            {{-- Title & Badges --}}
            <div class="mb-3">
                <div class="d-flex flex-wrap gap-2 mb-2">
                    @if($property->is_featured)
                        <span class="badge prop-badge prop-badge--featured">
                            <i class="bi bi-star-fill me-1"></i>{{ __('translation.properties.featured') }}
                        </span>
                    @endif
                    @if($property->operationType)
                        <span class="badge prop-badge prop-badge--{{ $property->operationType->code }}">
                            {{ $property->operationType->name }}
                        </span>
                    @endif
                    @if($property->propertyType)
                        <span class="badge prop-badge prop-badge--type">
                            {{ $property->propertyType->name }}
                        </span>
                    @endif
                </div>
                <h1 class="h3 fw-bold mb-1">{{ $property->title }}</h1>
                @if($property->address || $property->city)
                    <p class="text-muted d-flex align-items-center gap-1 mb-0">
                        <i class="bi bi-geo-alt-fill text-danger"></i>
                        {{ $property->address ?: $property->city?->name }}
                        @if($property->address && $property->city)
                            &mdash; {{ $property->city->name }}
                        @endif
                    </p>
                @endif
            </div>

            {{-- Price --}}
            <div class="prop-detail__price-box card border-0 bg-primary bg-opacity-10 mb-4">
                <div class="card-body py-3 px-4">
                    <span class="text-muted small">{{ __('translation.properties.price_label') }}</span>
                    <div class="fw-bold fs-2 text-primary">
                        {{ number_format((float)$property->price, 0, '.', ',') }}
                        <small class="fs-5 fw-normal text-muted">
                            {{ $property->currency?->name }}
                        </small>
                    </div>
                </div>
            </div>

            {{-- Specs Row (Bayut inline style) --}}
            <div class="prop-detail-specs mb-4">
                @if($property->area)
                    <div class="prop-detail-specs__item">
                        <i class="bi bi-aspect-ratio"></i>
                        <strong>{{ number_format((float)$property->area, 0) }}</strong> {{ __('translation.properties.m2') }}
                    </div>
                @endif
                @if($property->rooms)
                    <div class="prop-detail-specs__item">
                        <i class="bi bi-door-open"></i>
                        <strong>{{ $property->rooms }}</strong> {{ __('translation.properties.rooms') }}
                    </div>
                @endif
                @if($property->bathrooms)
                    <div class="prop-detail-specs__item">
                        <i class="bi bi-droplet"></i>
                        <strong>{{ $property->bathrooms }}</strong> {{ __('translation.properties.bathrooms') }}
                    </div>
                @endif
                @if($property->floor !== null)
                    <div class="prop-detail-specs__item">
                        <i class="bi bi-building"></i>
                        <strong>{{ $property->floor }}</strong> {{ __('translation.properties.floor') }}
                    </div>
                @endif
                @if($property->total_floors)
                    <div class="prop-detail-specs__item">
                        <i class="bi bi-layers"></i>
                        <strong>{{ $property->total_floors }}</strong> {{ __('translation.properties.total_floors') }}
                    </div>
                @endif
                @if($property->building_age)
                    <div class="prop-detail-specs__item">
                        <i class="bi bi-calendar3"></i>
                        <strong>{{ $property->building_age }}</strong> {{ __('translation.properties.building_age') }}
                    </div>
                @endif
            </div>

            {{-- Facts Table --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-body-tertiary py-2">
                    <h2 class="h6 mb-0 fw-bold">{{ __('translation.properties.specs_title') }}</h2>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped prop-facts-table mb-0">
                        <tbody>
                        @if($property->propertyType)
                            <tr>
                                <td>{{ __('translation.properties.property_type') }}</td>
                                <td class="fw-semibold">{{ $property->propertyType->name }}</td>
                            </tr>
                        @endif
                        @if($property->operationType)
                            <tr>
                                <td>{{ __('translation.properties.operation_type') }}</td>
                                <td class="fw-semibold">{{ $property->operationType->name }}</td>
                            </tr>
                        @endif
                        @if($property->area)
                            <tr>
                                <td>{{ __('translation.properties.area') }}</td>
                                <td class="fw-semibold">{{ number_format((float)$property->area, 0) }} {{ __('translation.properties.m2') }}</td>
                            </tr>
                        @endif
                        @if($property->rooms)
                            <tr>
                                <td>{{ __('translation.properties.rooms') }}</td>
                                <td class="fw-semibold">{{ $property->rooms }}</td>
                            </tr>
                        @endif
                        @if($property->bathrooms)
                            <tr>
                                <td>{{ __('translation.properties.bathrooms') }}</td>
                                <td class="fw-semibold">{{ $property->bathrooms }}</td>
                            </tr>
                        @endif
                        @if($property->floor !== null)
                            <tr>
                                <td>{{ __('translation.properties.floor') }}</td>
                                <td class="fw-semibold">{{ $property->floor }}</td>
                            </tr>
                        @endif
                        @if($property->total_floors)
                            <tr>
                                <td>{{ __('translation.properties.total_floors') }}</td>
                                <td class="fw-semibold">{{ $property->total_floors }}</td>
                            </tr>
                        @endif
                        @if($property->building_age)
                            <tr>
                                <td>{{ __('translation.properties.building_age') }}</td>
                                <td class="fw-semibold">{{ $property->building_age }}</td>
                            </tr>
                        @endif
                        @if($property->city)
                            <tr>
                                <td>{{ __('translation.properties.city') }}</td>
                                <td class="fw-semibold">{{ $property->city->name }}</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Description --}}
            @if($property->description)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-body-tertiary py-2">
                        <h2 class="h6 mb-0 fw-bold">{{ __('translation.properties.description') }}</h2>
                    </div>
                    <div class="card-body p-3">
                        <div class="prop-detail__description">
                            {!! nl2br(e($property->description)) !!}
                        </div>
                    </div>
                </div>
            @endif

            {{-- Map --}}
            @if($property->latitude && $property->longitude)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-body-tertiary py-2">
                        <h2 class="h6 mb-0 fw-bold">{{ __('translation.properties.location_map') }}</h2>
                    </div>
                    <div class="card-body p-0 overflow-hidden rounded-bottom-3">
                        <div class="prop-detail__map-placeholder d-flex flex-column align-items-center justify-content-center bg-light py-5">
                            <i class="bi bi-geo-alt-fill text-danger fs-1 mb-2"></i>
                            <div class="small text-muted">
                                {{ $property->latitude }}, {{ $property->longitude }}
                            </div>
                            <a href="https://maps.google.com/?q={{ $property->latitude }},{{ $property->longitude }}"
                               target="_blank" rel="noopener noreferrer"
                               class="btn btn-sm btn-outline-primary mt-2">
                                <i class="bi bi-box-arrow-up-right me-1"></i>
                                {{ __('translation.properties.open_map') }}
                            </a>
                        </div>
                    </div>
                </div>
            @endif

        </div>

        {{-- ── Sidebar ──────────────────────────────────────── --}}
        <div class="col-lg-4">
            <div class="sticky-lg-top" style="top: 80px">

                {{-- Contact Card --}}
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-body-tertiary py-2">
                        <h2 class="h6 mb-0 fw-bold">{{ __('translation.properties.contact_agent') }}</h2>
                    </div>
                    <div class="card-body p-3">
                        @if($property->user)
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="avatar-circle bg-primary text-white d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                                     style="width:48px;height:48px;font-size:1.2rem">
                                    {{ mb_substr($property->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $property->user->name }}</div>
                                    <div class="small text-muted">{{ __('translation.properties.owner') }}</div>
                                </div>
                            </div>
                        @endif

                        @auth
                            @if($property->user?->phone)
                                <a href="tel:{{ $property->user->phone }}"
                                   class="btn btn-primary w-100 mb-2">
                                    <i class="bi bi-telephone-fill me-2"></i>
                                    {{ __('translation.properties.call_agent') }}
                                </a>
                            @endif
                            @if($property->user?->email)
                                <a href="mailto:{{ $property->user->email }}"
                                   class="btn btn-outline-primary w-100">
                                    <i class="bi bi-envelope me-2"></i>
                                    {{ __('translation.properties.email_agent') }}
                                </a>
                            @endif
                        @else
                            <p class="small text-muted mb-2">{{ __('translation.properties.login_to_contact') }}</p>
                            <a href="{{ route('login') }}" class="btn btn-primary w-100">
                                <i class="bi bi-box-arrow-in-right me-2"></i>
                                {{ __('translation.auth.sign_in') }}
                            </a>
                        @endauth
                    </div>
                </div>

                {{-- Quick Details --}}
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-body-tertiary py-2">
                        <h2 class="h6 mb-0 fw-bold">{{ __('translation.properties.quick_details') }}</h2>
                    </div>
                    <ul class="list-group list-group-flush">
                        @if($property->city)
                            <li class="list-group-item d-flex justify-content-between small px-3 py-2">
                                <span class="text-muted">{{ __('translation.properties.city') }}</span>
                                <span class="fw-semibold">{{ $property->city->name }}</span>
                            </li>
                        @endif
                        @if($property->operationType)
                            <li class="list-group-item d-flex justify-content-between small px-3 py-2">
                                <span class="text-muted">{{ __('translation.properties.operation_type') }}</span>
                                <span class="fw-semibold">{{ $property->operationType->name }}</span>
                            </li>
                        @endif
                        @if($property->propertyType)
                            <li class="list-group-item d-flex justify-content-between small px-3 py-2">
                                <span class="text-muted">{{ __('translation.properties.property_type') }}</span>
                                <span class="fw-semibold">{{ $property->propertyType->name }}</span>
                            </li>
                        @endif
                        @if($property->area)
                            <li class="list-group-item d-flex justify-content-between small px-3 py-2">
                                <span class="text-muted">{{ __('translation.properties.area') }}</span>
                                <span class="fw-semibold">{{ number_format((float)$property->area, 0) }} {{ __('translation.properties.m2') }}</span>
                            </li>
                        @endif
                        @if($property->price)
                            <li class="list-group-item d-flex justify-content-between small px-3 py-2">
                                <span class="text-muted">{{ __('translation.properties.price_label') }}</span>
                                <span class="fw-semibold text-primary">
                                    {{ number_format((float)$property->price, 0, '.', ',') }}
                                    {{ $property->currency?->name }}
                                </span>
                            </li>
                        @endif
                        <li class="list-group-item d-flex justify-content-between small px-3 py-2">
                            <span class="text-muted">{{ __('translation.properties.posted_at') }}</span>
                            <span class="fw-semibold">{{ $property->created_at?->diffForHumans() }}</span>
                        </li>
                    </ul>
                </div>

                {{-- Share --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-3">
                        <div class="small fw-semibold mb-2">{{ __('translation.properties.share') }}</div>
                        <div class="d-flex gap-2">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                               target="_blank" rel="noopener noreferrer"
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-facebook"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($property->title) }}"
                               target="_blank" rel="noopener noreferrer"
                               class="btn btn-sm btn-outline-dark">
                                <i class="bi bi-twitter-x"></i>
                            </a>
                            <a href="https://wa.me/?text={{ urlencode($property->title . ' ' . request()->url()) }}"
                               target="_blank" rel="noopener noreferrer"
                               class="btn btn-sm btn-outline-success">
                                <i class="bi bi-whatsapp"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                    onclick="navigator.clipboard.writeText('{{ request()->url() }}').then(()=>{ this.innerHTML='<i class=\'bi bi-check\'></i>'; setTimeout(()=>{ this.innerHTML='<i class=\'bi bi-link-45deg\'></i>'; }, 2000); })">
                                <i class="bi bi-link-45deg"></i>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

    {{-- ── Related Properties ────────────────────────────────── --}}
    @if($relatedProperties->isNotEmpty())
        <hr class="my-5">
        <div>
            <h2 class="h5 fw-bold mb-4">{{ __('translation.properties.related_title') }}</h2>
            <div class="row g-3">
                @foreach($relatedProperties as $related)
                    <div class="col-6 col-lg-3">
                        <x-property-card :property="$related" />
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection

@push('scripts')
@if($imgCount > 1)
<script>
document.addEventListener('DOMContentLoaded', function () {
    var grid = document.getElementById('gallery-grid');
    var modal = document.getElementById('galleryModal');
    if (!grid || !modal) return;

    var bsModal = new bootstrap.Modal(modal);
    var swiper = null;

    grid.querySelectorAll('.prop-gallery-grid__item').forEach(function (item) {
        item.addEventListener('click', function () {
            var idx = parseInt(item.dataset.index) || 0;
            bsModal.show();
            setTimeout(function () {
                if (!swiper && typeof Swiper !== 'undefined') {
                    swiper = new Swiper('#gallerySwiper', {
                        spaceBetween: 10,
                        navigation: {
                            nextEl: '#gallerySwiper .swiper-button-next',
                            prevEl: '#gallerySwiper .swiper-button-prev',
                        },
                        pagination: { el: '#gallerySwiper .swiper-pagination', clickable: true },
                        initialSlide: idx,
                    });
                } else if (swiper) {
                    swiper.slideTo(idx, 0);
                }
            }, 200);
        });
    });
});
</script>
@endif
@endpush
