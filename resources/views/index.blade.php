@extends('layout.main')
@include('layout.extra_meta')
@section('content')
    <div class="container-fluid">
        <!-- Page Title Section -->
        <div class="page-title-section">
            <div class="row justify-content-center py-5">
                <div class="col-xxl-5 col-xl-7 text-center">
                <span class="badge badge-default fw-normal shadow px-2 py-1 mb-2 fst-italic fs-xxs">
                    <i class="bi bi-stars me-1"></i> {{ __('translation.app.name') }}
                </span>
                    <h3 class="fw-bold">{{ __('translation.dashboard.welcome') }}</h3>
                    <p class="fs-md text-muted mb-0">{{ __('translation.dashboard.welcome_subtitle') }}</p>
                </div>
            </div>
        </div>

        <!-- Stats Cards Row -->
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <!-- Users -->
                    <div class="col-md-6 col-xl-3 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="card-title mb-0">{{ __('translation.dashboard.stats.total_users') }}</h5>
                                    <i class="bi bi-people text-muted"></i>
                                </div>
                                <div class="text-center py-3">
                                    <h2 class="fw-bold mb-0 text-primary">--</h2>
                                    <span class="text-muted">{{ __('translation.dashboard.stats.total_users') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Specialties -->
                    <div class="col-md-6 col-xl-3 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="card-title mb-0">{{ __('translation.dashboard.stats.specialties') }}</h5>
                                    <i class="bi bi-hospital text-muted"></i>
                                </div>
                                <div class="text-center py-3">
                                    <h2 class="fw-bold mb-0 text-success">--</h2>
                                    <span class="text-muted">{{ __('translation.dashboard.stats.specialties') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Countries -->
                    <div class="col-md-6 col-xl-3 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="card-title mb-0">{{ __('translation.dashboard.stats.countries') }}</h5>
                                    <i class="bi bi-globe text-muted"></i>
                                </div>
                                <div class="text-center py-3">
                                    <h2 class="fw-bold mb-0 text-info">--</h2>
                                    <span class="text-muted">{{ __('translation.dashboard.stats.countries') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Configurations -->
                    <div class="col-md-6 col-xl-3 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="card-title mb-0">{{ __('translation.dashboard.stats.configurations') }}</h5>
                                    <i class="bi bi-gear text-muted"></i>
                                </div>
                                <div class="text-center py-3">
                                    <h2 class="fw-bold mb-0 text-warning">--</h2>
                                    <span class="text-muted">{{ __('translation.dashboard.stats.configurations') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Platform Overview -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-dashed">
                        <h4 class="card-title mb-0">{{ __('translation.dashboard.platform_stats') }}</h4>
                    </div>
                    <div class="card-body text-center py-5">
                        <i class="bi bi-box-seam display-4 text-muted opacity-50"></i>
                        <p class="text-muted mt-3 mb-0">{{ __('translation.dashboard.no_data') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


@push('scripts')

@endpush
