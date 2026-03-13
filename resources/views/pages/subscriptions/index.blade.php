@extends('layout.home.main')

@section('page_title', __('translation.subscriptions') . ' — ' . config('app.name'))

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="row mb-5">
        <div class="col-lg-8 mx-auto text-center">
            <h1 class="display-5 fw-bold mb-3">{{ __('translation.subscription.subscription_plans') }}</h1>
            <p class="lead text-muted">
                {{ __('translation.subscription.select_plan_description') }}
            </p>
        </div>
    </div>

    <!-- Subscriptions Grid -->
    <div class="row">
        @foreach($subscriptions as $subscription)
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card subscription-card h-100 border-2 {{ $subscription->is_current ? 'border-primary' : ($subscription->is_pending ? 'border-warning' : 'border-light') }} position-relative">
                    @if($subscription->is_current)
                        <div class="badge bg-primary position-absolute top-0 start-50 translate-middle">
                            {{ __('translation.subscription.current_plan') }}
                        </div>
                    @elseif($subscription->is_pending)
                        <div class="badge bg-warning position-absolute top-0 start-50 translate-middle">
                            {{ __('translation.subscription.pending_subscription') }}
                        </div>
                    @endif

                    <div class="card-body d-flex flex-column">
                        <!-- Icon -->
                        <div class="mb-3">
                            <i class="fas {{ $subscription->icon }} fa-3x" style="color: {{ $subscription->color }}"></i>
                        </div>

                        <!-- Plan Name -->
                        <h5 class="card-title fw-bold mb-3">{{ $subscription->name }}</h5>

                        <!-- Description -->
                        <p class="text-muted small mb-4">{{ $subscription->description }}</p>

                        <!-- Price -->
                        <div class="mb-4">
                            @if($subscription->key === 'free')
                                <span class="h4 fw-bold">{{ __('translation.subscription.free') }}</span>
                            @else
                                <div>
                                    <span class="h4 fw-bold">{{ number_format($subscription->price, 0) }}</span>
                                    <span class="text-muted">{{ $subscription->currency->name ?? 'SYP' }}</span>
                                </div>
                                <small class="text-muted">
                                    / {{ $subscription->duration_days }} {{ __('translation.subscription.days') }}
                                </small>
                            @endif
                        </div>

                        <!-- Properties Limit -->
                        <div class="alert alert-info py-2 mb-4">
                            <small class="fw-bold">
                                @if($subscription->max_properties === 0)
                                    {{ __('translation.subscription.unlimited_properties') }}
                                @else
                                    {{ $subscription->max_properties }} {{ __('translation.subscription.properties') }}
                                @endif
                            </small>
                        </div>

                        <!-- Features List -->
                        <ul class="list-unstyled small mb-4 flex-grow-1">
                            <li class="mb-2">
                                <i class="fas fa-check text-success"></i>
                                {{ __('translation.subscription.feature_basic_listings') }}
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success"></i>
                                {{ __('translation.subscription.feature_search') }}
                            </li>
                            @if($subscription->key !== 'free')
                                <li class="mb-2">
                                    <i class="fas fa-check text-success"></i>
                                    {{ __('translation.subscription.feature_priority_support') }}
                                </li>
                            @endif
                            @if($subscription->key === 'professional' || $subscription->key === 'enterprise')
                                <li class="mb-2">
                                    <i class="fas fa-check text-success"></i>
                                    {{ __('translation.subscription.feature_analytics') }}
                                </li>
                            @endif
                        </ul>

                        <!-- CTA Button -->
                        @auth
                            @if($subscription->is_current)
                                <button class="btn btn-outline-primary w-100" disabled>
                                    {{ __('translation.subscription.current_plan') }}
                                </button>
                            @elseif($subscription->is_pending)
                                <div class="alert alert-warning py-2 px-3 mb-0">
                                    <small class="fw-bold">
                                        <i class="fas fa-clock me-2"></i>
                                        {{ __('translation.subscription.contact_admin_activate') }}
                                    </small>
                                </div>
                            @else
                                <form action="{{ route('subscriptions.upgrade') }}" method="POST" class="w-100">
                                    @csrf
                                    <input type="hidden" name="subscription_id" value="{{ $subscription->id }}">
                                    <button type="submit" class="btn btn-primary w-100">
                                        {{ __('translation.subscription.upgrade') }}
                                    </button>
                                </form>
                            @endif
                        @else
                            <a href="{{ route('login.otp') }}" class="btn btn-primary w-100">
                                {{ __('translation.subscription.select_plan') }}
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Feature Comparison Section -->
    <div class="row mt-5">
        <div class="col-12">
            <h3 class="fw-bold mb-4">{{ __('translation.subscription.feature_comparison') }}</h3>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('translation.subscription.features') }}</th>
                            @foreach($subscriptions as $subscription)
                                <th class="text-center">{{ $subscription->name }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ __('translation.subscription.listings') }}</td>
                            @foreach($subscriptions as $subscription)
                                <td class="text-center">
                                    @if($subscription->max_properties === 0)
                                        <i class="fas fa-infinity text-success fa-lg"></i>
                                    @else
                                        {{ $subscription->max_properties }}
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>{{ __('translation.subscription.images_per_listing') }}</td>
                            @foreach($subscriptions as $subscription)
                                <td class="text-center">
                                    <i class="fas fa-check text-success fa-lg"></i>
                                </td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>{{ __('translation.subscription.featured_listings') }}</td>
                            @foreach($subscriptions as $subscription)
                                <td class="text-center">
                                    @if($subscription->key !== 'free')
                                        <i class="fas fa-check text-success fa-lg"></i>
                                    @else
                                        <i class="fas fa-times text-danger fa-lg"></i>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="row mt-5">
        <div class="col-lg-8 mx-auto">
            <h3 class="fw-bold mb-4">{{ __('translation.subscription.frequently_asked_questions') }}</h3>
            <div class="accordion" id="faqAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faqOne">
                            {{ __('translation.subscription.faq_can_change_plans') }}
                        </button>
                    </h2>
                    <div id="faqOne" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            {{ __('translation.subscription.faq_answer_change_plans') }}
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqTwo">
                            {{ __('translation.subscription.faq_cancel_subscription') }}
                        </button>
                    </h2>
                    <div id="faqTwo" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            {{ __('translation.subscription.faq_answer_cancel_subscription') }}
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqThree">
                            {{ __('translation.subscription.faq_billing_date') }}
                        </button>
                    </h2>
                    <div id="faqThree" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            {{ __('translation.subscription.faq_answer_billing_date') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .subscription-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .subscription-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

    .subscription-card.border-primary {
        background-color: #f0f7ff;
    }
</style>
@endsection
