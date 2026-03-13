@extends('layout.home.main')

@section('page_title', __('translation.property.edit_property') . ' — ' . config('app.name'))

@push('styles')
@vite('resources/css/properties.css')
@endpush

@section('content')
{{-- Page Hero --}}
<div class="prop-form-hero">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb small mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}" class="text-white-50 text-decoration-none">
                        <i class="fas fa-home me-1"></i>{{ __('translation.common.home') }}
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('properties.index') }}" class="text-white-50 text-decoration-none">{{ __('translation.property.properties') }}</a>
                </li>
                <li class="breadcrumb-item active text-white">{{ __('translation.property.edit_property') }}</li>
            </ol>
        </nav>
        <h1 class="prop-form-hero__title">{{ __('translation.property.edit_property') }}</h1>
        <p class="prop-form-hero__sub">{{ $property->title }}</p>
    </div>
</div>

{{-- Main Form --}}
<div class="container py-4">
    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        <strong>{{ __('translation.general.validation_errors') }}</strong>
        <ul class="mb-0 mt-1 ps-3 small">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <form action="{{ route('properties.update', $property) }}" method="POST" enctype="multipart/form-data" id="propertyForm">
        @csrf
        @method('PUT')

        <div class="row g-4">
            {{-- ── MAIN COLUMN ── --}}
            <div class="col-lg-8">

                {{-- STEP 1: Basic Information --}}
                <div class="prop-form-card">
                    <div class="prop-form-card__header">
                        <span class="prop-form-card__step">1</span>
                        <h2 class="prop-form-card__title">{{ __('translation.property.basic_information') }}</h2>
                    </div>
                    <div class="prop-form-card__body">
                        {{-- Title --}}
                        <div class="mb-4">
                            <label for="title" class="form-label fw-semibold">
                                {{ __('translation.general.title') }} <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control form-control-lg @error('title') is-invalid @enderror"
                                   id="title" name="title"
                                   value="{{ old('title', $property->title) }}"
                                   placeholder="{{ __('translation.property.title_placeholder') }}"
                                   required>
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Address --}}
                        <div class="mb-4">
                            <label for="address" class="form-label fw-semibold">
                                {{ __('translation.general.address') }} <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control @error('address') is-invalid @enderror"
                                   id="address" name="address"
                                   value="{{ old('address', $property->address) }}"
                                   placeholder="{{ __('translation.property.address_placeholder') }}"
                                   required>
                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Description --}}
                        <div class="mb-0">
                            <label for="description" class="form-label fw-semibold">
                                {{ __('translation.general.description') }} <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description"
                                      rows="5"
                                      placeholder="{{ __('translation.property.description_placeholder') }}"
                                      required>{{ old('description', $property->description) }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                {{-- STEP 2: Property Details --}}
                <div class="prop-form-card">
                    <div class="prop-form-card__header">
                        <span class="prop-form-card__step">2</span>
                        <h2 class="prop-form-card__title">{{ __('translation.property.property_details') }}</h2>
                    </div>
                    <div class="prop-form-card__body">

                        {{-- Row: City / Type / Operation --}}
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label for="city_id" class="form-label fw-semibold">
                                    {{ __('translation.property.city') }} <span class="text-danger">*</span>
                                </label>
                                <select class="form-select select2 @error('city_id') is-invalid @enderror"
                                        id="city_id" name="city_id" required>
                                    <option value="">{{ __('translation.general.select') }}</option>
                                    @foreach($filterOptions['cities'] as $city)
                                        <option value="{{ $city->id }}" @selected(old('city_id', $property->city_id) == $city->id)>{{ $city->name }}</option>
                                    @endforeach
                                </select>
                                @error('city_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label for="property_type_id" class="form-label fw-semibold">
                                    {{ __('translation.property.type') }} <span class="text-danger">*</span>
                                </label>
                                <select class="form-select select2 @error('property_type_id') is-invalid @enderror"
                                        id="property_type_id" name="property_type_id" required>
                                    <option value="">{{ __('translation.general.select') }}</option>
                                    @foreach($filterOptions['property_types'] as $type)
                                        <option value="{{ $type->id }}" @selected(old('property_type_id', $property->property_type_id) == $type->id)>{{ $type->name }}</option>
                                    @endforeach
                                </select>
                                @error('property_type_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label for="operation_type_id" class="form-label fw-semibold">
                                    {{ __('translation.property.operation') }} <span class="text-danger">*</span>
                                </label>
                                <select class="form-select select2 @error('operation_type_id') is-invalid @enderror"
                                        id="operation_type_id" name="operation_type_id" required>
                                    <option value="">{{ __('translation.general.select') }}</option>
                                    @foreach($filterOptions['operation_types'] as $operation)
                                        <option value="{{ $operation->id }}" @selected(old('operation_type_id', $property->operation_type_id) == $operation->id)>{{ $operation->name }}</option>
                                    @endforeach
                                </select>
                                @error('operation_type_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        {{-- Row: Price / Currency / Area --}}
                        <div class="row g-3 mb-4">
                            <div class="col-md-5">
                                <label for="price" class="form-label fw-semibold">
                                    {{ __('translation.property.price') }} <span class="text-danger">*</span>
                                </label>
                                <div class="input-group prop-price-group">
                                    <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                    <input type="number" step="0.01" min="0"
                                           class="form-control @error('price') is-invalid @enderror"
                                           id="price" name="price"
                                           value="{{ old('price', $property->price) }}" required>
                                    @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="currency_id" class="form-label fw-semibold">
                                    {{ __('translation.property.currency') }} <span class="text-danger">*</span>
                                </label>
                                <select class="form-select select2 @error('currency_id') is-invalid @enderror"
                                        id="currency_id" name="currency_id" required>
                                    <option value="">{{ __('translation.general.select') }}</option>
                                    @foreach($filterOptions['currencies'] as $currency)
                                        <option value="{{ $currency->id }}" @selected(old('currency_id', $property->currency_id) == $currency->id)>{{ $currency->code }}</option>
                                    @endforeach
                                </select>
                                @error('currency_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label for="area" class="form-label fw-semibold">
                                    {{ __('translation.property.area') }} (م²) <span class="text-danger">*</span>
                                </label>
                                <div class="input-group prop-price-group">
                                    <span class="input-group-text"><i class="fas fa-vector-square"></i></span>
                                    <input type="number" step="0.01" min="0"
                                           class="form-control @error('area') is-invalid @enderror"
                                           id="area" name="area"
                                           value="{{ old('area', $property->area) }}" required>
                                    @error('area')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        {{-- Counter fields: Rooms / Bathrooms / Floor / Total floors / Building age --}}
                        <div class="row g-3">
                            <div class="col-6 col-md-4">
                                <label class="form-label fw-semibold">
                                    {{ __('translation.property.rooms') }} <span class="text-danger">*</span>
                                </label>
                                <div class="prop-counter @error('rooms') is-invalid @enderror">
                                    <button type="button" class="prop-counter__btn" data-target="rooms" data-action="dec">−</button>
                                    <input type="number" class="prop-counter__input" id="rooms" name="rooms"
                                           value="{{ old('rooms', $property->rooms) }}" min="0" required>
                                    <button type="button" class="prop-counter__btn" data-target="rooms" data-action="inc">+</button>
                                </div>
                                @error('rooms')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-6 col-md-4">
                                <label class="form-label fw-semibold">
                                    {{ __('translation.property.bathrooms') }} <span class="text-danger">*</span>
                                </label>
                                <div class="prop-counter @error('bathrooms') is-invalid @enderror">
                                    <button type="button" class="prop-counter__btn" data-target="bathrooms" data-action="dec">−</button>
                                    <input type="number" class="prop-counter__input" id="bathrooms" name="bathrooms"
                                           value="{{ old('bathrooms', $property->bathrooms) }}" min="0" required>
                                    <button type="button" class="prop-counter__btn" data-target="bathrooms" data-action="inc">+</button>
                                </div>
                                @error('bathrooms')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-6 col-md-4">
                                <label class="form-label fw-semibold">{{ __('translation.property.floor') }}</label>
                                <div class="prop-counter @error('floor') is-invalid @enderror">
                                    <button type="button" class="prop-counter__btn" data-target="floor" data-action="dec">−</button>
                                    <input type="number" class="prop-counter__input" id="floor" name="floor"
                                           value="{{ old('floor', $property->floor ?? 0) }}" min="0">
                                    <button type="button" class="prop-counter__btn" data-target="floor" data-action="inc">+</button>
                                </div>
                                @error('floor')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-6 col-md-4">
                                <label class="form-label fw-semibold">{{ __('translation.property.total_floors') }}</label>
                                <div class="prop-counter @error('total_floors') is-invalid @enderror">
                                    <button type="button" class="prop-counter__btn" data-target="total_floors" data-action="dec">−</button>
                                    <input type="number" class="prop-counter__input" id="total_floors" name="total_floors"
                                           value="{{ old('total_floors', $property->total_floors ?? 0) }}" min="0">
                                    <button type="button" class="prop-counter__btn" data-target="total_floors" data-action="inc">+</button>
                                </div>
                                @error('total_floors')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-6 col-md-4">
                                <label class="form-label fw-semibold">{{ __('translation.property.building_age') }}</label>
                                <div class="prop-counter @error('building_age') is-invalid @enderror">
                                    <button type="button" class="prop-counter__btn" data-target="building_age" data-action="dec">−</button>
                                    <input type="number" class="prop-counter__input" id="building_age" name="building_age"
                                           value="{{ old('building_age', $property->building_age ?? 0) }}" min="0">
                                    <button type="button" class="prop-counter__btn" data-target="building_age" data-action="inc">+</button>
                                </div>
                                @error('building_age')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- STEP 3: Images --}}
                <div class="prop-form-card">
                    <div class="prop-form-card__header">
                        <span class="prop-form-card__step">3</span>
                        <h2 class="prop-form-card__title">{{ __('translation.property.property_images') }}</h2>
                    </div>
                    <div class="prop-form-card__body">
                        <x-property-images-upload :property="$property" :existingImages="$currentImages" :isEditMode="true" formId="propertyForm" />
                    </div>
                </div>

                {{-- STEP 4: Location --}}
                <div class="prop-form-card">
                    <div class="prop-form-card__header">
                        <span class="prop-form-card__step">4</span>
                        <h2 class="prop-form-card__title">{{ __('translation.property.location') }}</h2>
                    </div>
                    <div class="prop-form-card__body">
                        {{-- Interactive Leaflet Map --}}
                        <div class="prop-map-container">
                            <div id="propertyMap"></div>
                        </div>
                        <div class="prop-map-hint mt-2">
                            <i class="fas fa-hand-pointer"></i>
                            {{ __('translation.property.click_map_to_pin') }}
                        </div>
                        {{-- Hidden inputs - values set by map --}}
                        <input type="hidden" id="latitude"  name="latitude"  value="{{ old('latitude', $property->latitude) }}">
                        <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude', $property->longitude) }}">
                    </div>
                </div>

            </div>{{-- /col-lg-8 --}}

            {{-- ── SIDEBAR ── --}}
            <div class="col-lg-4">

                {{-- Submit card --}}
                <div class="prop-form-submit">
                    <div class="prop-form-submit__header">
                        <p class="prop-form-submit__title">
                            <i class="fas fa-floppy-disk me-2"></i>{{ __('translation.property.save_changes') }}
                        </p>
                    </div>
                    <div class="prop-form-submit__body">
                        <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold mb-3">
                            <i class="fas fa-save me-2"></i>{{ __('translation.property.update_property') }}
                        </button>
                        <a href="{{ route('properties.show', $property->slug) }}"
                           class="btn btn-outline-secondary w-100 mb-3">
                            {{ __('translation.general.cancel') }}
                        </a>
                    </div>
                </div>

                {{-- Options --}}
                <div class="prop-form-options mt-3">
                    <div class="prop-form-options__header">
                        <h6 class="mb-0 fw-bold">
                            <i class="fas fa-sliders text-primary me-2"></i>{{ __('translation.property.options') }}
                        </h6>
                    </div>
                    <div class="prop-form-options__body">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1"
                                   @checked(old('is_featured', $property->is_featured))>
                            <label class="form-check-label fw-semibold" for="is_featured">
                                {{ __('translation.property.featured_property') }}
                            </label>
                            <small class="d-block text-muted">{{ __('translation.property.featured_description') }}</small>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="active" name="active" value="1"
                                   @checked(old('active', $property->active))>
                            <label class="form-check-label fw-semibold" for="active">
                                {{ __('translation.property.active') }}
                            </label>
                            <small class="d-block text-muted">{{ __('translation.property.active_description') }}</small>
                        </div>
                        <div>
                            <label for="agency_id" class="form-label fw-semibold">{{ __('translation.property.agency') }}</label>
                            @if($userAgency)
                                {{-- User has an agency - show as read-only --}}
                                <div class="alert alert-info mb-0">
                                    <i class="fas fa-building me-2"></i>
                                    <strong>{{ $userAgency->name }}</strong>
                                    <small class="d-block text-muted mt-1">{{ __('translation.property.agency_auto_assigned') }}</small>
                                </div>
                            @else
                                {{-- User doesn't have an agency - show dropdown --}}
                                <select class="form-select select2 @error('agency_id') is-invalid @enderror" id="agency_id" name="agency_id">
                                    <option value="">{{ __('translation.general.none') }}</option>
                                    @foreach($filterOptions['agencies'] as $agency)
                                        <option value="{{ $agency->id }}" @selected(old('agency_id', $property->agency_id) == $agency->id)>{{ $agency->name }}</option>
                                    @endforeach
                                </select>
                                @error('agency_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Tips --}}
                <div class="prop-tips">
                    <p class="prop-tips__title">
                        <i class="fas fa-lightbulb me-2"></i>{{ __('translation.property.helpful_tips') }}
                    </p>
                    <ul>
                        <li>{{ __('translation.property.tip_1') }}</li>
                        <li>{{ __('translation.property.tip_2') }}</li>
                        <li>{{ __('translation.property.tip_3') }}</li>
                    </ul>
                </div>

            </div>{{-- /col-lg-4 --}}
        </div>{{-- /row --}}
    </form>
</div>{{-- /container --}}
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Counter buttons
    document.querySelectorAll('.prop-counter__btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const input = document.getElementById(btn.dataset.target);
            const step  = parseInt(input.step) || 1;
            let val     = parseInt(input.value) || 0;
            if (btn.dataset.action === 'inc') val += step;
            else val = Math.max(parseInt(input.min) || 0, val - step);
            input.value = val;
        });
    });

    // Submit form with uploaded images
    document.getElementById('propertyForm').addEventListener('submit', async function (e) {
        e.preventDefault();
        const form = this;
        const submitBtn = form.querySelector('[type="submit"]');
        if (submitBtn) { submitBtn.disabled = true; }

        const formData = new FormData(form);
        // Replace any existing images[] entries with explicitly appended new image files
        formData.delete('images[]');
        if (window.propertyUploadedFiles && window.propertyUploadedFiles.length > 0) {
            window.propertyUploadedFiles.forEach(item => formData.append('images[]', item.file, item.file.name));
        }

        try {
            const response = await fetch(form.action, { method: 'POST', body: formData });
            if (response.ok) {
                window.location.href = response.url;
            } else {
                const errorData = await response.json();
                console.error('Form submission error:', errorData);
                alert(errorData.message || '{{ __("translation.general.error_occurred") }}');
                if (submitBtn) { submitBtn.disabled = false; }
            }
        } catch (err) {
            console.error('Submit error:', err);
            alert('{{ __("translation.general.error_occurred") }}');
            if (submitBtn) { submitBtn.disabled = false; }
        }
    });
});
</script>

{{-- Leaflet Map --}}
<script>
(function () {
    const defaultLat = 35.954307;
    const defaultLng = 39.009404;
    const latInput   = document.getElementById('latitude');
    const lngInput   = document.getElementById('longitude');

    window.ensureLeaflet().then(function (leaflet) {
        const L = leaflet.default || leaflet;

        delete L.Icon.Default.prototype._getIconUrl;
        L.Icon.Default.mergeOptions({
            iconUrl:       '{{ asset('build/assets/marker-icon-hN30_KVU.png') }}',
            iconRetinaUrl: '{{ asset('build/assets/marker-icon-hN30_KVU.png') }}',
            shadowUrl:     '{{ asset('build/assets/layers-BWBAp2CZ.png') }}',
        });

        const initLat = parseFloat(latInput.value) || defaultLat;
        const initLng = parseFloat(lngInput.value) || defaultLng;

        const propMap = L.map('propertyMap').setView([initLat, initLng], 15);

        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri',
            maxZoom: 19
        }).addTo(propMap);

        const marker = L.marker([initLat, initLng], { draggable: true }).addTo(propMap);

        function updateCoords(lat, lng) {
            latInput.value = lat.toFixed(6);
            lngInput.value = lng.toFixed(6);
        }

        propMap.on('click', function (e) {
            marker.setLatLng(e.latlng);
            updateCoords(e.latlng.lat, e.latlng.lng);
        });

        marker.on('dragend', function () {
            const pos = marker.getLatLng();
            updateCoords(pos.lat, pos.lng);
        });
    });
})();
</script>
@endpush