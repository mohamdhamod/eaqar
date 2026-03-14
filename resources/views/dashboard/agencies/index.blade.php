@extends('layout.main')

@section('page_title', __('translation.agency.manage_agencies') . ' — ' . config('app.name'))

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-1">
                <i class="fas fa-building me-2" style="color: #667eea;"></i>
                {{ __('translation.agency.manage_agencies') }}
            </h2>
            <p class="text-muted mb-0">{{ __('translation.agency.manage_agencies_description') }}</p>
        </div>
        <div class="col-md-4 text-md-end">
            <span class="badge bg-primary">{{ $agencies->total() }} {{ __('translation.agency.total_agencies') }}</span>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="GET" action="{{ route('agencies.index') }}" class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label"><strong>{{ __('translation.common.status') }}</strong></label>
                            <select class="form-select select2" name="status" onchange="this.form.submit()">
                                <option value="all" {{ $currentStatus === 'all' ? 'selected' : '' }}>{{ __('translation.common.all') }}</option>
                                <option value="active" {{ $currentStatus === 'active' ? 'selected' : '' }}>{{ __('translation.common.active') }}</option>
                                <option value="pending" {{ $currentStatus === 'pending' ? 'selected' : '' }}>{{ __('translation.common.pending') }}</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Agencies Table -->
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light border-bottom">
                    <tr>
                        <th class="ps-4">{{ __('translation.common.name') }}</th>
                        <th>{{ __('translation.common.email') }}</th>
                        <th>{{ __('translation.agency.location') }}</th>
                        <th class="text-center">{{ __('translation.common.status') }}</th>
                        <th class="text-center">{{ __('translation.common.created_at') }}</th>
                        <th class="text-center pe-4">{{ __('translation.common.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($agencies as $agency)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-2">
                                    @if($agency->logo)
                                        <img src="{{ asset('storage/' . $agency->logo) }}" 
                                             alt="{{ $agency->name }}"
                                             class="rounded-circle"
                                             width="40"
                                             height="40"
                                             style="object-fit: cover;">
                                    @else
                                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                                             style="width: 40px; height: 40px;">
                                            <i class="fas fa-building text-muted"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <strong>{{ $agency->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $agency->user->name }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <a href="mailto:{{ $agency->user->email }}" class="text-decoration-none">
                                    {{ $agency->user->email }}
                                </a>
                            </td>
                            <td>
                                <i class="fas fa-map-marker-alt text-muted me-2 d-flex align-items-center justify-content-center" style="font-size: 0.85rem;"></i>
                                {{ $agency->address ?? '—' }}
                            </td>
                            <td class="text-center">
                                @if($agency->is_active)
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1 d-flex align-items-center justify-content-center"></i>
                                        {{ __('translation.common.active') }}
                                    </span>
                                @else
                                    <span class="badge bg-warning">
                                        <i class="fas fa-hourglass-half me-1 d-flex align-items-center justify-content-center"></i>
                                        {{ __('translation.common.pending') }}
                                    </span>
                                @endif
                            </td>
                            <td class="text-center text-muted small d-flex align-items-center justify-content-center">
                                {{ $agency->created_at->format('d M Y') }}
                            </td>
                            <td class="text-center pe-4">
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" 
                                            class="btn btn-outline-primary toggle-agency-status"
                                            data-agency-id="{{ $agency->id }}"
                                            data-current-status="{{ $agency->is_active ? 'true' : 'false' }}"
                                            title="{{ $agency->is_active ? __('translation.agency.deactivate') : __('translation.agency.activate') }}">
                                        <i class="fas {{ $agency->is_active ? 'fa-check' : 'fa-times' }} me-1"></i>
                                        {{ $agency->is_active ? __('translation.common.active') : __('translation.common.pending') }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="fas fa-inbox text-muted mb-3" style="font-size: 2rem;"></i>
                                <p class="text-muted">{{ __('translation.agency.no_agencies') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($agencies->hasPages())
        <div class="row mt-4">
            <div class="col-12">
                {{ $agencies->links() }}
            </div>
        </div>
    @endif
</div>

<style>
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }
</style>

@push('scripts')
    @include('modules.confirm_activate')
    @include('modules.i18n', ['page' => 'agencies'])
    <script>
        const i18n = window.i18n;

        document.addEventListener('DOMContentLoaded', function() {
            // Activate/Deactivate button handler
            document.querySelectorAll('.toggle-agency-status').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const agencyId = this.dataset.agencyId;
                    const currentStatus = this.dataset.currentStatus === 'true';
                    
                    const data = {
                        id: agencyId,
                        name: this.closest('tr').querySelector('strong').textContent,
                        active: currentStatus
                    };
                    
                    confirmActivate(data, `{{ route('agencies.index') }}/${agencyId}/toggle-active`, i18n);
                });
            });
        });
    </script>
@endpush
@endsection
