<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\PermissionEnum;
use App\Http\Controllers\Controller;
use App\Services\AgencyService;
use App\Services\UserSubscriptionService;
use Illuminate\Http\Request;

class AgenciesController extends Controller
{
    /**
     * Constructor with permission middleware.
     */
    public function __construct(
        protected AgencyService $agencyService,
        protected UserSubscriptionService $userSubscriptionService
    ) {
        $this->middleware('permission:' . PermissionEnum::MANAGE_AGENCIES_VIEW)->only(['index']);
        $this->middleware('permission:' . PermissionEnum::MANAGE_AGENCIES_ADD)->only(['create', 'store']);
        $this->middleware('permission:' . PermissionEnum::MANAGE_AGENCIES_UPDATE)->only(['edit', 'update', 'updateActiveStatus']);
        $this->middleware('permission:' . PermissionEnum::MANAGE_AGENCIES_DELETE)->only(['destroy']);
    }

    /**
     * Display a listing of agencies
     */
    public function index(Request $request)
    {
        $agencies = $this->agencyService->getAdminAgencies(
            $request->status ?? 'all',
            15
        );

        return view('dashboard.agencies.index', [
            'agencies' => $agencies,
            'currentStatus' => $request->status ?? 'all',
        ]);
    }

    /**
     * Update agency active status
     */
    public function updateActiveStatus(Request $request, $lang, $id)
    {
        try {
            // Accept both 'active' boolean and 'action' string from different sources
            if ($request->has('action')) {
                $newStatus = $request->input('action') === 'activate';
            } else {
                $newStatus = $request->boolean('active');
            }

            $this->agencyService->updateAgencyStatus(
                $id,
                $newStatus,
                $this->userSubscriptionService
            );

            return response()->json([
                'success' => true,
                'message' => __('translation.messages.updated_successfully'),
                'status' => $newStatus ? 'active' : 'pending',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('translation.messages.error_occurred')
            ], 500);
        }
    }
}
