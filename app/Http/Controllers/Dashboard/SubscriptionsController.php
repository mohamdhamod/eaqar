<?php

namespace App\Http\Controllers\Dashboard;

use App\DTO\SubscriptionDTO;
use App\Enums\PermissionEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionRequest;
use App\Models\Subscription;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;

class SubscriptionsController extends Controller
{
    public function __construct(
        protected SubscriptionService $service
    ) {
        $this->middleware('permission:' . PermissionEnum::MANAGE_SUBSCRIPTIONS_VIEW)->only(['index']);
        $this->middleware('permission:' . PermissionEnum::MANAGE_SUBSCRIPTIONS_ADD)->only(['store']);
        $this->middleware('permission:' . PermissionEnum::MANAGE_SUBSCRIPTIONS_UPDATE)->only(['update', 'updateActiveStatus']);
        $this->middleware('permission:' . PermissionEnum::MANAGE_SUBSCRIPTIONS_DELETE)->only(['destroy']);
    }

    /**
     * Display a listing of subscriptions.
     */
    public function index(Request $request)
    {
        $subscriptions = $this->service->getPaginated(12);

        return view('dashboard.subscriptions.index', ['subscriptions' => $subscriptions]);
    }

    /**
     * Store a newly created subscription.
     */
    public function store(SubscriptionRequest $request)
    {
        try {
            $dto = SubscriptionDTO::fromArray($request->validated());
            $subscription = $this->service->create($dto);

            return response()->json([
                'success' => true,
                'redirect' => route('subscriptions.index'),
                'message' => __('translation.messages.added_successfully')
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified subscription.
     */
    public function update(SubscriptionRequest $request, $lang, $id)
    {
        try {
            $subscription = $this->service->getById($id);
            $dto = SubscriptionDTO::fromArray(array_merge(
                $request->validated(),
                ['id' => $subscription->id]
            ));
            
            $this->service->update($subscription->id, $dto);

            return response()->json([
                'success' => true,
                'redirect' => route('subscriptions.index'),
                'message' => __('translation.messages.updated_successfully')
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified subscription.
     */
    public function destroy($lang, $id)
    {
        try {
            $this->service->delete($id);

            return response()->json([
                'success' => true,
                'redirect' => route('subscriptions.index'),
                'message' => __('translation.messages.deleted_successfully')
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle the active status of a subscription.
     */
    public function updateActiveStatus($lang, $id = null)
    {
        try {
            $subscription = $this->service->toggleActive($id);

            return response()->json([
                'success' => true,
                'redirect' => route('subscriptions.index'),
                'message' => __('translation.messages.activated_successfully')
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
