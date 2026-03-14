@extends('layout.home.main')

@section('page_title', __('translation.agency.setup_title') . ' — ' . config('app.name'))

@section('content')

{{-- Hero Section --}}
<section class="ag-hero">
    <div class="container position-relative z-1">
        <nav class="ag-hero__breadcrumb mb-2">
            <a href="{{ route('home') }}">{{ __('translation.layout.home.nav_home') }}</a>
            <span class="mx-1 text-white-50">/</span>
            <span class="active">{{ $agency ? __('translation.common.edit') : __('translation.common.confirm') }}</span>
        </nav>
        <h1 class="ag-hero__title mb-0">
            {{ $agency ? __('translation.agency.edit_agency_info') : __('translation.agency.become_agent') }}
        </h1>
        <p class="ag-hero__sub mb-0">
            {{ $agency ? __('translation.agency.manage_listings_portfolio') : __('translation.agency.join_thousands_agents') }}
        </p>
    </div>
</section>

{{-- Main Content --}}
<div class="container pb-5 mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            {{-- Success Message --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle fs-5 me-3" style="color: #10b981;"></i>
                        <div>
                            <strong>{{ __('translation.common.success') }}!</strong> {{ session('success') }}
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Status Badge (if editing) --}}
            @if($agency)
                <div class="alert @if($agency->is_active) alert-success @else alert-warning @endif mb-4 border-0">
                    <div class="d-flex align-items-center">
                        <i class="fas @if($agency->is_active) fa-check-circle @else fa-hourglass-half @endif fs-5 me-3"></i>
                        <div>
                            @if($agency->is_active)
                                <strong>{{ __('translation.agency.status_active') }}</strong> — {{ __('translation.agency.agency_approved') }}
                            @else
                                <strong>{{ __('translation.agency.status_pending_review') }}</strong> — {{ __('translation.agency.agency_pending') }}
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            {{-- Form Card --}}
            <div class="agency-form-card">
                {{-- Form Header --}}
                <div class="form-header mb-4">
                    <h3 class="h4 fw-bold text-dark">
                        <i class="fas fa-building me-2" style="color: #667eea;"></i>
                        {{ $agency ? __('translation.agency.edit_agency_info') : __('translation.agency.agency_details') }}
                    </h3>
                </div>

                <form action="{{ $agency ? route('agency.update') : route('agency.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if($agency)
                        @method('PUT')
                    @endif

                    <!-- Agency Name -->
                    <div class="mb-4">
                        <label for="name" class="form-label fw-600">
                            {{ __('translation.agency.agency_name') }}
                            <span class="text-danger ms-1">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fas fa-text-width text-muted"></i>
                            </span>
                            <input 
                                type="text" 
                                class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                id="name" 
                                name="name" 
                                value="{{ old('name', $agency?->name) }}" 
                                placeholder="{{ __('translation.agency.agency_name_placeholder') }}"
                                required
                            >
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="d-block text-muted mt-2">{{ __('translation.agency.how_buyers_see_agency') }}</small>
                    </div>

                    <!-- Logo Upload Section -->
                    <div class="mb-4">
                        <label for="logo" class="form-label fw-600">{{ __('translation.agency.agency_logo') }}</label>
                        
                        {{-- Current Logo Display --}}
                        @if($agency?->logo)
                            <div class="current-logo-preview mb-3">
                                <div class="bg-light p-3 rounded border position-relative" style="display: inline-block;">
                                    <img 
                                        src="{{ asset('storage/' . $agency->logo) }}" 
                                        alt="{{ $agency->name }}" 
                                        class="rounded"
                                        style="max-height: 80px; max-width: 200px; object-fit: contain;"
                                    >
                                </div>
                                <small class="d-block text-muted mt-2">{{ __('translation.agency.upload_new_logo') }}</small>
                            </div>
                        @endif

                        {{-- Logo Upload Input --}}
                        <div class="logo-upload-area">
                            <input 
                                type="file" 
                                class="form-control @error('logo') is-invalid @enderror" 
                                id="logo" 
                                name="logo" 
                                accept="image/*"
                                onchange="previewLogo(this)"
                            >
                            <small class="d-block text-muted mt-2">
                                <i class="fas fa-info-circle me-1"></i>
                                {{ __('translation.agency.logo_recommendation') }}
                            </small>
                            @error('logo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Logo Preview --}}
                        <div id="logoPreview" class="mt-3" style="display: none;">
                            <div class="bg-light p-3 rounded border">
                                <img id="previewImg" style="max-height: 80px; max-width: 200px; object-fit: contain;" alt="Preview">
                            </div>
                            <small class="d-block text-success mt-2">
                                <i class="fas fa-check-circle me-1"></i> {{ __('translation.agency.image_preview') }}
                            </small>
                        </div>
                    </div>

                    <!-- Address Field -->
                    <div class="mb-4">
                        <label for="address" class="form-label fw-600">{{ __('translation.agency.agency_address') }}</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fas fa-map-marker-alt text-muted"></i>
                            </span>
                            <input 
                                type="text" 
                                class="form-control form-control-lg @error('address') is-invalid @enderror" 
                                id="address" 
                                name="address" 
                                value="{{ old('address', $agency?->address) }}" 
                                placeholder="{{ __('translation.agency.agency_address_placeholder') }}"
                            >
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="d-block text-muted mt-2">{{ __('translation.agency.where_agency_located') }}</small>
                    </div>

                    {{-- Form Actions --}}
                    <div class="form-actions pt-4 border-top">
                        <div class="row g-2">
                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary btn-lg px-5">
                                    <i class="fas {{ $agency ? 'fa-save' : 'fa-check' }} me-2"></i>
                                    {{ $agency ? __('translation.common.save_changes') : __('translation.agency.submit_application') }}
                                </button>
                            </div>
                            <div class="col-auto">
                                <a href="{{ $agency ? route('agency.index') : route('home') }}" class="btn btn-outline-secondary btn-lg px-5">
                                    <i class="fas fa-times me-2"></i> {{ __('translation.common.cancel') }}
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Information Note --}}
                    @if(!$agency)
                        <div class="info-banner mt-4 p-4 bg-light-blue rounded border border-info">
                            <h6 class="fw-bold text-primary mb-2">
                                <i class="fas fa-lightbulb me-2"></i> {{ __('translation.agency.important_information') }}
                            </h6>
                            <ul class="small mb-0 ps-3">
                                <li>{{ __('translation.agency.agency_under_review') }}</li>
                                <li>{{ __('translation.agency.application_under_review') }}</li>
                                <li>{{ __('translation.agency.can_add_listings') }}</li>
                            </ul>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>

{{-- JavaScript for Logo Preview --}}
<script>
    function previewLogo(input) {
        const preview = document.getElementById('logoPreview');
        const previewImg = document.getElementById('previewImg');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.style.display = 'block';
            };
            
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.style.display = 'none';
        }
    }
</script>

@endsection
