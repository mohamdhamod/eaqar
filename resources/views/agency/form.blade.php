@extends('layout.home.main')

@section('page_title', __('translation.agency.setup_title') . ' — ' . config('app.name'))

@section('content')

{{-- Modern Page Header with Gradient --}}
<div class="agency-hero">
    <div class="agency-hero-overlay"></div>
    <div class="container">
        <div class="row align-items-center min-vh-20">
            <div class="col-lg-8">
                <h1 class="display-5 fw-bold text-white mb-3">
                    {{ $agency ? __('translation.agency.edit_agency_info') : __('translation.agency.become_agent') }}
                </h1>
                <p class="lead text-white-75 mb-0">
                    {{ $agency ? __('translation.agency.manage_listings_portfolio') : __('translation.agency.join_thousands_agents') }}
                </p>
            </div>
        </div>
    </div>
</div>

{{-- Breadcrumb Navigation --}}
<div class="container pt-4 pb-3">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small mb-0">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}" class="text-decoration-none">
                    <i class="fas fa-home me-1"></i> {{ __('translation.common.back_to_home') }}
                </a>
            </li>
            <li class="breadcrumb-item active">{{ $agency ? __('translation.common.edit') : __('translation.common.confirm') }}</li>
        </ol>
    </nav>
</div>

{{-- Main Content --}}
<div class="container pb-5">
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


<style>
    /* Hero Section */
    .agency-hero {
        position: relative;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 260px;
        overflow: hidden;
    }

    .agency-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            radial-gradient(circle at 20% 50%, rgba(255,255,255,0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 80%, rgba(255,255,255,0.05) 0%, transparent 50%);
        pointer-events: none;
    }

    .agency-hero-overlay {
        position: absolute;
        inset: 0;
        pointer-events: none;
    }

    .agency-hero .container {
        position: relative;
        z-index: 2;
    }

    .min-vh-20 {
        min-height: 20vh;
    }

    .text-white-75 {
        color: rgba(255, 255, 255, 0.75);
    }

    /* Form Card */
    .agency-form-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        padding: 2.5rem;
        border: 1px solid #e5e7eb;
        transition: box-shadow 0.3s ease;
    }

    .agency-form-card:hover {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
    }

    /* Form Header */
    .form-header {
        padding-bottom: 1.5rem;
        border-bottom: 2px solid #f0f0f0;
    }

    .form-header h3 {
        font-size: 1.5rem;
        letter-spacing: -0.5px;
    }

    /* Form Labels */
    .form-label {
        display: block;
        font-weight: 600;
        color: #1f2937;
        font-size: 0.95rem;
        margin-bottom: 0.7rem;
        letter-spacing: -0.3px;
    }

    .fw-600 {
        font-weight: 600 !important;
    }

    /* Form Controls */
    .form-control,
    .input-group-text {
        border-radius: 8px;
        border: 1.5px solid #d1d5db;
        transition: all 0.3s ease;
    }

    .form-control {
        font-size: 1rem;
        padding: 0.75rem 1rem;
    }

    .form-control-lg {
        padding: 0.875rem 1rem;
        font-size: 1rem;
        border-radius: 8px;
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .input-group-text {
        background: #f9fafb;
        border-right: none;
        color: #6b7280;
    }

    .input-group .form-control {
        border-left: none;
    }

    /* Logo Upload Area */
    .logo-upload-area {
        position: relative;
    }

    .current-logo-preview {
        padding: 1rem;
        background: #f9fafb;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
    }

    /* Info Banner */
    .info-banner {
        background: #eff6ff;
        border-color: #bfdbfe !important;
    }

    .info-banner h6 {
        color: #1e40af;
    }

    .info-banner ul {
        color: #1e3a8a;
    }

    .bg-light-blue {
        background-color: #eff6ff;
    }

    /* Form Actions */
    .form-actions {
        margin-top: 2rem;
    }

    /* Buttons */
    .btn {
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        padding: 0.75rem 1.5rem;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #5568d3 0%, #653b8a 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    .btn-lg {
        padding: 0.875rem 2rem;
        font-size: 1rem;
    }

    .btn-outline-secondary {
        border: 2px solid #d1d5db;
        color: #374151;
    }

    .btn-outline-secondary:hover {
        background: #f9fafb;
        border-color: #9ca3af;
        color: #1f2937;
    }

    /* Alerts */
    .alert {
        border-radius: 8px;
        border: 1px solid;
        background-color: #fff;
    }

    .alert-success {
        border-color: #d1fae5;
        background-color: #f0fdf4;
        color: #065f46;
    }

    .alert-warning {
        border-color: #fed7aa;
        background-color: #fffbeb;
        color: #92400e;
    }

    /* Breadcrumb */
    .breadcrumb {
        background: transparent;
        padding: 0;
    }

    .breadcrumb-item a {
        color: #667eea;
        font-weight: 500;
        transition: color 0.3s ease;
    }

    .breadcrumb-item a:hover {
        color: #764ba2;
        text-decoration: underline;
    }

    .breadcrumb-item.active {
        color: #6b7280;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .agency-hero {
            min-height: 200px;
        }

        .agency-hero .display-5 {
            font-size: 2rem;
        }

        .agency-form-card {
            padding: 1.5rem;
        }

        .form-actions .row > div {
            flex: 1 0 100%;
        }

        .btn-lg {
            width: 100%;
        }
    }

    /* Dark Mode Support */
    @media (prefers-color-scheme: dark) {
        .agency-form-card {
            background: #1f2937;
            border-color: #374151;
        }

        .form-label {
            color: #f3f4f6;
        }

        .form-control,
        .input-group-text {
            background: #111827;
            border-color: #4b5563;
            color: #f3f4f6;
        }

        .form-control:focus {
            border-color: #667eea;
        }

        .input-group-text {
            background: #111827;
        }

        .info-banner {
            background: #1e3a8a;
            border-color: #1e40af !important;
        }

        .info-banner h6 {
            color: #93c5fd;
        }

        .info-banner ul {
            color: #bfdbfe;
        }
    }

    /* File Input Custom Styling */
    .form-control[type="file"]::file-selector-button {
        background: #f3f4f6;
        border: none;
        border-radius: 6px;
        padding: 0.5rem 1rem;
        color: #374151;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .form-control[type="file"]::file-selector-button:hover {
        background: #e5e7eb;
    }
</style>

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
