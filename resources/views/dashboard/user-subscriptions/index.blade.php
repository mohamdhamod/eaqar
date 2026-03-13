@extends('layout.main')

@section('page_title', __('translation.user_subscriptions.manage_user_subscriptions') . ' — ' . config('app.name'))

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-1">
                <i class="fas fa-star me-2" style="color: #667eea;"></i>
                {{ __('translation.user_subscriptions.manage_user_subscriptions') }}
            </h2>
            <p class="text-muted mb-0">{{ __('translation.user_subscriptions.manage_user_subscriptions_description') }}</p>
        </div>
        <div class="col-md-4 text-md-end">
            <span class="badge bg-primary">{{ $userSubscriptions->total() }} {{ __('translation.user_subscriptions.total_subscriptions') }}</span>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="GET" action="{{ route('user-subscriptions.index') }}" class="row g-3 align-items-end">
                        <!-- Status Filter -->
                        <div class="col-md-3">
                            <label class="form-label"><strong>{{ __('translation.common.status') }}</strong></label>
                            <select class="form-select select2" name="status" onchange="this.form.submit()">
                                <option value="all" {{ $currentStatus === 'all' ? 'selected' : '' }}>{{ __('translation.common.all') }}</option>
                                <option value="active" {{ $currentStatus === 'active' ? 'selected' : '' }}>{{ __('translation.common.active') }}</option>
                                <option value="inactive" {{ $currentStatus === 'inactive' ? 'selected' : '' }}>{{ __('translation.common.inactive') }}</option>
                            </select>
                        </div>

                        <!-- Subscription Filter -->
                        <div class="col-md-3">
                            <label class="form-label"><strong>{{ __('translation.subscription.subscription') }}</strong></label>
                            <select class="form-select select2" name="subscription_id" onchange="this.form.submit()">
                                <option value="">{{ __('translation.common.all_subscriptions') }}</option>
                                @foreach($subscriptions as $subscription)
                                    <option value="{{ $subscription->id }}" {{ $currentSubscriptionFilter == $subscription->id ? 'selected' : '' }}>
                                        {{ $subscription->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Search -->
                        <div class="col-md-4">
                            <label class="form-label"><strong>{{ __('translation.common.search') }}</strong></label>
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="{{ __('translation.common.search_by_email_or_name') }}" value="{{ $searchQuery }}">
                                <button class="btn btn-outline-primary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Reset -->
                        @if($currentStatus !== 'all' || $currentSubscriptionFilter || $searchQuery)
                            <div class="col-md-2 text-end">
                                <a href="{{ route('user-subscriptions.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-redo me-1"></i> {{ __('translation.common.reset') }}
                                </a>
                            </div>
                        @endif
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

    <!-- User Subscriptions Table -->
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light border-bottom">
                    <tr>
                        <th class="ps-4">{{ __('translation.common.user') }}</th>
                        <th>{{ __('translation.subscription.subscription') }}</th>
                        <th class="text-center">{{ __('translation.user_subscriptions.properties_used') }}</th>
                        <th class="text-center">{{ __('translation.common.status') }}</th>
                        <th class="text-center">{{ __('translation.user_subscriptions.started_at') }}</th>
                        <th class="text-center pe-4">{{ __('translation.common.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($userSubscriptions as $userSub)
                        <tr>
                            <td class="ps-4">
                                <div>
                                    <strong>{{ $userSub->user->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $userSub->user->email }}</small>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fas {{ $userSub->subscription->icon }}" style="color: {{ $userSub->subscription->color }}; width: 24px;"></i>
                                    <div>
                                        <strong>{{ $userSub->subscription->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ __('translation.subscription.key') }}: {{ $userSub->subscription->key }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                @if($userSub->subscription->max_properties === 0)
                                    <span class="badge bg-info">{{ __('translation.subscription.unlimited') }}</span>
                                @else
                                    <strong>{{ $userSub->used_properties }} / {{ $userSub->subscription->max_properties }}</strong>
                                    @php
                                        $percentage = round(($userSub->used_properties / $userSub->subscription->max_properties) * 100);
                                        $color = $percentage >= 80 ? 'danger' : ($percentage >= 50 ? 'warning' : 'success');
                                    @endphp
                                    <br>
                                    <small class="text-muted">{{ $percentage }}%</small>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($userSub->active)
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i>
                                        {{ __('translation.common.active') }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-minus-circle me-1"></i>
                                        {{ __('translation.common.inactive') }}
                                    </span>
                                @endif
                            </td>
                            <td class="text-center text-muted small">
                                {{ $userSub->started_at->format('d M Y') }}
                            </td>
                            <td class="text-center pe-4">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <button class="dropdown-item toggle-subscription-status" 
                                                    data-subscription-id="{{ $userSub->id }}"
                                                    data-current-status="{{ $userSub->active ? 'true' : 'false' }}">
                                                <i class="fas {{ $userSub->active ? 'fa-times' : 'fa-check' }} me-2"></i>
                                                {{ $userSub->active ? __('translation.user_subscriptions.deactivate') : __('translation.user_subscriptions.activate') }}
                                            </button>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <button class="dropdown-item change-subscription" 
                                                    data-subscription-id="{{ $userSub->id }}"
                                                    data-user-id="{{ $userSub->user_id }}">
                                                <i class="fas fa-exchange-alt me-2"></i>
                                                {{ __('translation.user_subscriptions.change_subscription') }}
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="fas fa-inbox text-muted mb-3" style="font-size: 2rem;"></i>
                                <p class="text-muted">{{ __('translation.user_subscriptions.no_subscriptions') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($userSubscriptions->hasPages())
        <div class="row mt-4">
            <div class="col-12">
                {{ $userSubscriptions->links() }}
            </div>
        </div>
    @endif
</div>

<!-- Change Subscription Modal -->
<div class="modal fade" id="changeSubscriptionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header bg-light border-0">
                <h5 class="modal-title">
                    <i class="fas fa-exchange-alt me-2" style="color: #667eea;"></i>
                    {{ __('translation.user_subscriptions.change_subscription') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="changeSubscriptionForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label"><strong>{{ __('translation.subscription.subscription') }}</strong></label>
                        <select name="subscription_id" class="form-select select2" id="newSubscriptionSelect" required>
                            <option value="">{{ __('translation.common.select') }}</option>
                            @foreach($subscriptions as $subscription)
                                <option value="{{ $subscription->id }}">
                                    {{ $subscription->name }} ({{ __('translation.subscription.key') }}: {{ $subscription->key }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        {{ __('translation.common.cancel') }}
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check me-2"></i> {{ __('translation.common.confirm') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }
</style>

@push('scripts')
    @include('modules.confirm_activate')
    @include('modules.i18n', ['page' => 'user-subscriptions'])
    <script>
        const i18n = window.i18n;

        document.addEventListener('DOMContentLoaded', function() {
            // Activate/Deactivate button handler
            document.querySelectorAll('.toggle-subscription-status').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const subscriptionId = this.dataset.subscriptionId;
                    const currentStatus = this.dataset.currentStatus === 'true';
                    
                    const data = {
                        id: subscriptionId,
                        name: this.closest('tr').querySelector('strong').textContent,
                        active: currentStatus
                    };
                    
                    confirmActivate(data, `{{ route('user-subscriptions.index') }}/${subscriptionId}/toggle-active`, i18n);
                });
            });

            // Change subscription
            document.querySelectorAll('.change-subscription').forEach(button => {
                button.addEventListener('click', function() {
                    const subscriptionId = this.dataset.subscriptionId;
                    const modal = new bootstrap.Modal(document.getElementById('changeSubscriptionModal'));

                    document.getElementById('changeSubscriptionForm').action = `{{ route('user-subscriptions.index') }}/${subscriptionId}/change-subscription`;
                    modal.show();
                });
            });
        });
    </script>
@endpush
@endsection
