<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPropertySubscription
{
    public function handle(Request $request, Closure $next): Response
    {
        $user         = $request->user();
        $subscription = $user?->currentSubscription();

        // No active subscription → redirect to subscriptions page
        if (! $subscription) {
            return redirect()->route('subscriptions.index')
                ->with('error', __('translation.subscriptions.no_active_subscription'));
        }

        if ($subscription->hasReachedLimit()) {
            return redirect()->route('subscriptions.index')
                ->with('error', __('translation.subscriptions.limit_reached'));
        }

        return $next($request);
    }
}
