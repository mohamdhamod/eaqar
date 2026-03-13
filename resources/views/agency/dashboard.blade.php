@extends('layout.home.main')

@section('page_title', __('translation.agency.my_agency') . ' — ' . config('app.name'))

@section('content')

{{-- Modern Hero Section --}}
<div class="dashboard-hero">
    <div class="dashboard-hero-overlay"></div>
    <div class="container-fluid">
        <div class="row align-items-center min-vh-20">
            <div class="col-lg-8">
                <h1 class="dashboard-title text-white mb-2">
                    {{ __('translation.agency.my_agency_dashboard') }}
                </h1>
                <p class="dashboard-subtitle text-white-75 mb-0">
                    {{ __('translation.agency.manage_properties_listings') }}
                </p>
            </div>
        </div>
    </div>
</div>

{{-- Breadcrumb --}}
<div class="container-fluid pt-3 pb-2">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small mb-0">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}" class="text-decoration-none breadcrumb-link">
                    <i class="fas fa-home me-2"></i> {{ __('translation.common.back_to_home') }}
                </a>
            </li>
            <li class="breadcrumb-item active">{{ __('translation.agency.my_agency') }}</li>
        </ol>
    </nav>
</div>

<div class="container-fluid pb-4 mt-4">
    {{-- Success Alert --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle fs-6 me-2" style="color: #10b981;"></i>
                <div>
                    <strong>{{ __('translation.common.success') }}!</strong> {{ session('success') }}
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Agency Info Card - Professional Layout --}}
    <div class="agency-info-card mb-4">
        <div class="row g-3">
            {{-- Logo Section --}}
            <div class="col-md-2 col-sm-3">
                <div class="agency-logo-section">
                    @if($agency->logo)
                        <img 
                            src="{{ asset('storage/' . $agency->logo) }}" 
                            alt="{{ $agency->name }}" 
                            class="agency-logo rounded-2 w-100"
                        >
                    @else
                        <div class="agency-logo-placeholder rounded-2 d-flex align-items-center justify-content-center w-100 h-100">
                            <div class="text-center">
                                <i class="fas fa-building fa-3x text-muted mb-1 d-block"></i>
                                <small class="text-muted d-block">{{ __('translation.agency.agency_logo') }}</small>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Agency Info Section --}}
            <div class="col-md-5 col-sm-6">
                <div class="agency-details">
                    <h3 class="agency-name mb-2">{{ $agency->name }}</h3>
                    
                    {{-- Status Badge --}}
                    <div class="mb-3">
                        @if($agency->is_active)
                            <span class="badge badge-active">
                                <i class="fas fa-check-circle me-1"></i> {{ __('translation.agency.status_active') }}
                            </span>
                        @else
                            <span class="badge badge-pending">
                                <i class="fas fa-hourglass-half me-1"></i> {{ __('translation.agency.status_pending_review') }}
                            </span>
                        @endif
                    </div>

                    {{-- Address --}}
                    @if($agency->address)
                        <div class="info-item-compact">
                            <i class="fas fa-map-marker-alt info-icon-small"></i>
                            <div>
                                <small class="info-label">{{ __('translation.agency.agency_location') }}</small>
                                <p class="info-value-compact mb-0">{{ $agency->address }}</p>
                            </div>
                        </div>
                    @endif

                    {{-- Member Since --}}
                    <div class="info-item-compact">
                        <i class="fas fa-calendar info-icon-small"></i>
                        <div>
                            <small class="info-label">{{ __('translation.agency.member_since') }}</small>
                            <p class="info-value-compact mb-0">{{ $agency->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Stats Section --}}
            <div class="col-md-5">
                <div class="stats-grid-row">
                    <div class="stat-card-compact">
                        <div class="stat-icon-small">
                            <i class="fas fa-home"></i>
                        </div>
                        <div class="stat-content-compact">
                            <div class="stat-number-compact">{{ $properties->count() }}</div>
                            <div class="stat-label-compact">{{ __('translation.agency.active_listings') }}</div>
                        </div>
                    </div>

                    <div class="stat-card-compact">
                        <div class="stat-icon-small">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="stat-content-compact">
                            <div class="stat-number-compact">{{ auth()->user()->currentSubscription()?->subscription->name ?? __('translation.subscription.free') }}</div>
                            <div class="stat-label-compact">{{ __('translation.subscription.subscription') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="mt-3 pt-3 border-top">
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('agency.show') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-edit me-1"></i> {{ __('translation.agency.edit_agency_info') }}
                </a>
                <a href="{{ route('subscriptions.index') }}" class="btn btn-info btn-sm">
                    <i class="fas fa-star me-1"></i> {{ __('translation.subscription.manage_subscriptions') }}
                </a>
                @if($agency->is_active)
                    <a href="{{ route('properties.create') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-plus-circle me-1"></i> {{ __('translation.agency.add_new_listing') }}
                    </a>
                @else
                    <button class="btn btn-secondary btn-sm" disabled title="{{ __('translation.agency.available_after_approval') }}">
                        <i class="fas fa-lock me-1"></i> {{ __('translation.agency.add_new_listing') }}
                    </button>
                @endif
            </div>
        </div>
    </div>

    {{-- Subscription Info Card --}}
    @php
        $currentSubscription = auth()->user()->currentSubscription();
    @endphp
    @if($currentSubscription)
        <div class="subscription-info-card mb-4">
            <div class="row g-3 align-items-center">
                <div class="col-md-8">
                    <div class="subscription-details">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-star me-2" style="color: {{ $currentSubscription->subscription->color }}; font-size: 1.3rem;"></i>
                            <div>
                                <h6 class="mb-0">{{ __('translation.subscription.current_plan') }}: <strong>{{ $currentSubscription->subscription->name }}</strong></h6>
                                <small class="text-white-75">{{ $currentSubscription->subscription->description }}</small>
                            </div>
                        </div>

                        {{-- Subscription Details --}}
                        <div class="mt-3 pt-3 border-top border-white border-opacity-25">
                            <div class="row g-2 small">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-calendar-check me-2 text-white-75"></i>
                                        <div>
                                            <small class="text-white-75 d-block">{{ __('translation.subscription.start_date') }}</small>
                                            <small class="text-white fw-bold">{{ $currentSubscription->started_at->format('d M Y') }}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-calendar-times me-2 text-white-75"></i>
                                        <div>
                                            <small class="text-white-75 d-block">{{ __('translation.subscription.end_date') }}</small>
                                            <small class="text-white fw-bold">{{ $currentSubscription->expires_at ? $currentSubscription->expires_at->format('d M Y') : __('translation.subscription.unlimited') }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Days Remaining Info --}}
                        @if($currentSubscription->expires_at)
                            @php
                                $daysRemaining = (int) ceil(now()->diffInDays($currentSubscription->expires_at, false));
                            @endphp
                            <div class="mt-3 pt-3 border-top border-white border-opacity-25">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-hourglass-end me-2" style="font-size: 1.2rem;"></i>
                                    <div>
                                        @if($daysRemaining > 0)
                                            <small class="text-white-75 d-block">{{ __('translation.subscription.days_remaining') }}</small>
                                            <h6 class="mb-0 text-white fw-bold">{{ $daysRemaining }} {{ $daysRemaining == 1 ? __('translation.subscription.day_remaining') : __('translation.subscription.days_remaining') }}</h6>
                                        @elseif($daysRemaining == 0)
                                            <small class="text-white-75 d-block">{{ __('translation.subscription.days_remaining') }}</small>
                                            <h6 class="mb-0 text-warning fw-bold">{{ __('translation.subscription.expires_today') }}</h6>
                                        @else
                                            <small class="text-white-75 d-block">{{ __('translation.subscription.days_remaining') }}</small>
                                            <h6 class="mb-0 text-danger fw-bold">{{ __('translation.subscription.expired') }}</h6>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Properties Quota Bar --}}
                        <div class="mt-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label mb-0"><small><strong>{{ __('translation.subscription.properties') }}: {{ $currentSubscription->used_properties }}/{{ $currentSubscription->subscription->max_properties === 0 ? '∞' : $currentSubscription->subscription->max_properties }}</strong></small></label>
                            </div>
                            @if($currentSubscription->subscription->max_properties > 0)
                                <div class="progress" style="height: 20px;">
                                    @php
                                        $percentage = round(($currentSubscription->used_properties / $currentSubscription->subscription->max_properties) * 100);
                                        $progressColor = $percentage >= 80 ? 'danger' : ($percentage >= 50 ? 'warning' : 'success');
                                    @endphp
                                    <div class="progress-bar bg-{{ $progressColor }}" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                                        <small class="d-flex justify-content-center align-items-center h-100 text-white fw-bold">{{ $percentage }}%</small>
                                    </div>
                                </div>
                                <small class="text-white-75 mt-1 d-block">{{ __('translation.subscription.remaining') }}: {{ $currentSubscription->remainingPropertiesQuota() }} {{ __('translation.subscription.properties') }}</small>
                            @else
                                <div class="alert alert-success mb-0 py-1 px-2">
                                    <i class="fas fa-infinity me-1"></i> <small>{{ __('translation.subscription.unlimited_properties') }}</small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-4 text-md-end">
                    <a href="{{ route('subscriptions.index') }}" class="btn btn-outline-light btn-sm w-100 w-md-auto">
                        <i class="fas fa-arrow-up me-1"></i> {{ __('translation.subscription.upgrade') }}
                    </a>
                </div>
            </div>
        </div>
    @endif

    {{-- Listings Section --}}
    <div class="listings-section">
        <div class="section-header mb-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h5 class="section-title mb-1">{{ __('translation.agency.my_listings') }}</h5>
                    <small class="section-subtitle mb-0">{{ $properties->count() }} {{ trans_choice('translation.property.property|properties', $properties->count()) }}</small>
                </div>
                @if($agency->is_active)
                    <a href="{{ route('properties.create') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-plus me-1"></i> {{ __('translation.agency.add_listing') }}
                    </a>
                @endif
            </div>
        </div>

        {{-- Properties Grid --}}
        @if($properties->count() > 0)
            <div class="row g-2">
                @foreach($properties as $property)
                    <div class="col-6 col-md-4 col-lg-3">
                        <x-property-card :property="$property" />
                    </div>
                @endforeach
            </div>
        @else
            {{-- Empty State --}}
            <div class="empty-state-container">
                <div class="empty-state-card">
                    <div class="empty-state-icon mb-2">
                        <i class="fas fa-building fa-1x"></i>
                    </div>
                    <h6 class="empty-state-title mb-1">{{ __('translation.agency.no_listings_yet') }}</h6>
                    <p class="empty-state-text mb-3">
                        @if($agency->is_active)
                            {{ __('translation.agency.start_building_portfolio') }}
                        @else
                            {{ __('translation.agency.properties_waiting_approval') }}
                        @endif
                    </p>

                    @if($agency->is_active)
                        <a href="{{ route('properties.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus-circle me-1"></i> {{ __('translation.agency.add_first_listing') }}
                        </a>
                    @else
                        <p class="text-muted small">{{ __('translation.agency.application_under_review') }}</p>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>


<style>
    /* ===== Color Palette ===== */
    :root {
        --primary: #667eea;
        --primary-dark: #764ba2;
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        --text-primary: #1f2937;
        --text-secondary: #6b7280;
        --text-muted: #9ca3af;
        --border-light: #e5e7eb;
        --bg-light: #f9fafb;
        --bg-lighter: #f3f4f6;
    }

    /* ===== Hero Section ===== */
    .dashboard-hero {
        position: relative;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 200px;
        overflow: hidden;
    }

    .dashboard-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            radial-gradient(circle at 20% 50%, rgba(255,255,255,0.15) 0%, transparent 50%),
            radial-gradient(circle at 80% 80%, rgba(255,255,255,0.08) 0%, transparent 50%);
        pointer-events: none;
    }

    .dashboard-hero .container-fluid {
        position: relative;
        z-index: 2;
        padding: 0 1rem;
    }

    .dashboard-hero-overlay {
        position: absolute;
        inset: 0;
        pointer-events: none;
    }

    .dashboard-title {
        font-size: 1.75rem;
        font-weight: 800;
        letter-spacing: -1px;
        line-height: 1.2;
    }

    .dashboard-subtitle {
        font-size: 0.95rem;
        font-weight: 400;
        letter-spacing: 0.3px;
    }

    .min-vh-20 {
        min-height: 20vh;
    }

    .text-white-75 {
        color: rgba(255, 255, 255, 0.75);
    }

    .text-white-75 {
        color: rgba(255, 255, 255, 0.75);
    }

    .breadcrumb-link {
        color: var(--primary);
        font-weight: 500;
        transition: color 0.3s ease;
    }

    .breadcrumb-link:hover {
        color: var(--primary-dark);
    }

    /* ===== Agency Info Card ===== */
    .agency-info-card {
        background: #fff;
        border-radius: 12px;
        padding: 1.5rem 1rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.03);
        border: 1px solid var(--border-light);
        transition: all 0.3s ease;
        margin-top: -1.5rem;
        position: relative;
        z-index: 10;
    }

    .agency-info-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .agency-logo-section {
        aspect-ratio: 1;
        overflow: hidden;
    }

    .agency-logo {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
        border: 2px solid var(--primary);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
        transition: transform 0.3s ease;
    }

    .agency-logo:hover {
        transform: scale(1.02);
    }

    .agency-logo-placeholder {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, var(--bg-light) 0%, var(--bg-lighter) 100%);
        border: 2px solid var(--border-light);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* ===== Agency Details ===== */
    .agency-details {
        padding: 0;
    }

    .agency-name {
        font-size: 1.4rem;
        font-weight: 800;
        color: var(--text-primary);
        letter-spacing: -0.5px;
        margin-bottom: 0.5rem;
    }

    .badge-active {
        background: linear-gradient(135deg, var(--success) 0%, #059669 100%);
        color: white;
        padding: 0.4rem 0.8rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.75rem;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.2);
    }

    .badge-pending {
        background: linear-gradient(135deg, var(--warning) 0%, #d97706 100%);
        color: white;
        padding: 0.4rem 0.8rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.75rem;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        box-shadow: 0 2px 8px rgba(245, 158, 11, 0.2);
    }

    .info-item-compact {
        display: flex;
        gap: 0.5rem;
        align-items: flex-start;
        padding: 0.5rem 0;
        border-bottom: 1px solid var(--border-light);
    }

    .info-item-compact:hover {
        padding: 0.5rem 0.5rem;
        margin: 0 -0.5rem;
        background: rgba(102, 126, 234, 0.04);
        border-radius: 8px;
        border-bottom-color: transparent;
    }

    .info-item-compact:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .info-icon-small {
        width: 32px;
        height: 32px;
        min-width: 32px;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.15) 0%, rgba(118, 75, 162, 0.15) 100%);
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        font-size: 0.9rem;
        flex-shrink: 0;
        border: none;
        transition: all 0.3s ease;
        box-shadow: 0 1px 4px rgba(102, 126, 234, 0.12);
        margin-top: 0.15rem;
        align-self: center;
    }

    .info-item-compact:hover .info-icon-small {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.22) 0%, rgba(118, 75, 162, 0.22) 100%);
        box-shadow: 0 3px 8px rgba(102, 126, 234, 0.18);
        transform: scale(1.08);
    }

    .info-label {
        display: block;
        font-size: 0.65rem;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.2rem;
        opacity: 0.85;
    }

    .info-value-compact {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-primary);
        line-height: 1.3;
        margin: 0;
    }

    /* ===== Stats Grid ===== */
    .stats-grid-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .stat-card-compact {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
        border: 2px solid rgba(102, 126, 234, 0.15);
        border-radius: 10px;
        padding: 1rem;
        display: flex;
        align-items: center;
        gap: 0.8rem;
        transition: all 0.3s ease;
    }

    .stat-card-compact:hover {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.12) 0%, rgba(118, 75, 162, 0.12) 100%);
        border-color: rgba(102, 126, 234, 0.25);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
    }

    .stat-icon-small {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .stat-content-compact {
        flex: 1;
    }

    .stat-number-compact {
        font-size: 1.3rem;
        font-weight: 800;
        color: var(--text-primary);
        line-height: 1.2;
    }

    .stat-label-compact {
        font-size: 0.75rem;
        color: var(--text-secondary);
        font-weight: 600;
        margin-top: 0.2rem;
    }

    /* ===== Listings Section ===== */
    .listings-section {
        margin-top: 1.5rem;
    }

    .section-header {
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--border-light);
    }

    .section-title {
        font-size: 1.3rem;
        font-weight: 800;
        color: var(--text-primary);
        letter-spacing: -0.5px;
        margin: 0;
    }

    .section-subtitle {
        font-size: 0.85rem;
        color: var(--text-secondary);
        font-weight: 500;
    }

    /* ===== Subscription Info Card ===== */
    .subscription-info-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        padding: 1.5rem 1rem;
        box-shadow: 0 4px 16px rgba(102, 126, 234, 0.2);
        color: white;
        margin-top: 1.5rem;
        border: 1px solid rgba(255, 255, 255, 0.15);
        position: relative;
        overflow: hidden;
    }

    .subscription-info-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: -50px;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 50%;
        pointer-events: none;
    }

    .subscription-info-card::after {
        content: '';
        position: absolute;
        bottom: -50px;
        left: -50px;
        width: 150px;
        height: 150px;
        background: rgba(255, 255, 255, 0.06);
        border-radius: 50%;
        pointer-events: none;
    }

    .subscription-details {
        position: relative;
        z-index: 1;
    }

    .subscription-info-card h6 {
        color: rgba(255, 255, 255, 0.95);
        font-size: 0.95rem;
    }

    .subscription-info-card .text-white-75 {
        color: rgba(255, 255, 255, 0.75);
        font-size: 0.85rem;
    }

    .subscription-info-card .text-muted {
        color: rgba(255, 255, 255, 0.75);
    }

    .subscription-info-card .form-label {
        color: rgba(255, 255, 255, 0.95);
    }

    .subscription-info-card .alert {
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.25);
        color: rgba(255, 255, 255, 0.95);
        padding: 0.5rem 0.75rem;
        font-size: 0.85rem;
    }

    .subscription-info-card .btn-outline-light {
        color: white;
        border-color: rgba(255, 255, 255, 0.3);
        background: rgba(255, 255, 255, 0.1);
    }

    .subscription-info-card .btn-outline-light:hover {
        background: rgba(255, 255, 255, 0.2);
        border-color: rgba(255, 255, 255, 0.5);
        color: white;
    }

    @media (max-width: 767.98px) {
        .subscription-info-card {
            padding: 1.25rem 0.75rem;
        }

        .w-md-auto {
            width: 100% !important;
        }
    }

    /* ===== Empty State ===== */
    .empty-state-container {
        padding: 2rem 0;
    }

    .empty-state-card {
        background: linear-gradient(135deg, var(--bg-light) 0%, var(--bg-lighter) 100%);
        border: 2px dashed var(--border-light);
        border-radius: 12px;
        padding: 2.5rem 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
    }

    .empty-state-card:hover {
        border-color: var(--primary);
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.02) 0%, rgba(118, 75, 162, 0.02) 100%);
    }

    .empty-state-icon {
        color: var(--text-muted);
        word-spacing: 100vw;
    }

    .empty-state-title {
        font-size: 1.2rem;
        font-weight: 800;
        color: var(--text-primary);
    }

    .empty-state-text {
        color: var(--text-secondary);
        font-size: 0.9rem;
    }

    /* ===== Buttons ===== */
    .btn-primary {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        border: none;
        color: white;
        font-weight: 600;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
        font-size: 0.9rem;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(102, 126, 234, 0.3);
        color: white;
    }

    .btn-success {
        background: linear-gradient(135deg, var(--success) 0%, #059669 100%);
        border: none;
        color: white;
        font-weight: 600;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
        font-size: 0.9rem;
    }

    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(16, 185, 129, 0.3);
        color: white;
    }

    .btn-info {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        border: none;
        color: white;
        font-weight: 600;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
        font-size: 0.9rem;
    }

    .btn-info:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(59, 130, 246, 0.3);
        color: white;
    }

    .btn-secondary:disabled {
        background: var(--text-muted);
        border: none;
        cursor: not-allowed;
        opacity: 0.7;
    }

    .btn-outline-primary {
        color: var(--primary);
        border: 2px solid var(--primary);
        background: transparent;
        font-weight: 600;
        border-radius: 8px;
        transition: all 0.3s ease;
        font-size: 0.85rem;
        padding: 0.4rem 0.8rem;
    }

    .btn-outline-primary:hover {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white;
        border-color: transparent;
    }

    .btn-sm {
        padding: 0.4rem 0.8rem;
        font-size: 0.85rem;
    }

    /* ===== Responsive ===== */
    @media (max-width: 768px) {
        .dashboard-title {
            font-size: 1.5rem;
        }

        .agency-name {
            font-size: 1.2rem;
        }

        .agency-info-card {
            padding: 1rem 0.75rem;
            margin-top: -1rem;
        }

        .stat-number-compact {
            font-size: 1.1rem;
        }

        .section-title {
            font-size: 1.1rem;
        }

        .stats-grid-row {
            grid-template-columns: 1fr 1fr;
            gap: 0.8rem;
        }

        .stat-card-compact {
            padding: 0.8rem;
            gap: 0.6rem;
        }

        .stat-icon-small {
            width: 35px;
            height: 35px;
            font-size: 1rem;
        }

        .stat-content-compact {
            flex: 1;
        }

        .stat-label-compact {
            font-size: 0.7rem;
        }

        .info-item-compact {
            padding: 0.4rem 0;
            gap: 0.4rem;
        }

        .info-icon-small {
            width: 28px;
            height: 28px;
            font-size: 0.8rem;
        }

        .btn-lg {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }

        .empty-state-card {
            padding: 1.5rem 1rem;
        }

        .empty-state-title {
            font-size: 1rem;
        }

        .empty-state-text {
            font-size: 0.85rem;
        }
    }

    @media (max-width: 576px) {
        .dashboard-title {
            font-size: 1.3rem;
        }

        .agency-name {
            font-size: 1.1rem;
        }

        .agency-info-card {
            padding: 0.75rem;
        }

        .agency-logo-section {
            max-width: 120px;
            margin: 0 auto;
        }

        .stat-number-compact {
            font-size: 1rem;
        }

        .stat-label-compact {
            font-size: 0.65rem;
        }

        .section-title {
            font-size: 1rem;
        }

        .section-subtitle {
            font-size: 0.8rem;
        }

        .breadcrumb {
            font-size: 0.8rem;
        }

        .btn {
            padding: 0.35rem 0.7rem;
            font-size: 0.8rem;
        }

        .badge-active, .badge-pending {
            padding: 0.3rem 0.6rem;
            font-size: 0.7rem;
        }
    }
</style>

@endsection
