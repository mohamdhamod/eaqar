<?php

namespace App\Services;

use App\DTO\AgencyDTO;
use App\Enums\RoleEnum;
use App\Models\Agency;
use App\Repositories\AgencyRepository;
use App\Traits\FileHandler;
use Illuminate\Support\Facades\DB;

class AgencyService
{
    use FileHandler;

    public function __construct(
        private readonly AgencyRepository $agencyRepository,
    ) {}

    /**
     * Register user as an agent with agency.
     * Creates both Agency model and assigns Agent role to user.
     */
    public function registerAgent(int $userId, AgencyDTO $dto): Agency
    {
        DB::beginTransaction();
        try {
            $dto->userId = $userId;
            $dto->isActive = false; // New agencies are inactive by default

            $agency = $this->agencyRepository->create($dto);

            // Assign Agent role to user
            $user = $agency->user;
            $user->assignRole(RoleEnum::AGENT);

            DB::commit();
            return $agency;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Get active agencies for public listing with optional search.
     */
    public function getPublicAgencies(int $perPage = 20, ?string $search = null)
    {
        return $this->agencyRepository->getActivePaginatedWithCounts($perPage, $search);
    }

    /**
     * Get user's agency (for agents).
     */
    public function getUserAgency(int $userId): ?Agency
    {
        return $this->agencyRepository->getByUserId($userId);
    }

    /**
     * Update agency information.
     */
    public function updateAgency(int $agencyId, AgencyDTO $dto): Agency
    {
        return $this->agencyRepository->update($agencyId, $dto);
    }

    /**
     * Activate an agency (admin only).
     */
    public function activateAgency(int $agencyId): Agency
    {
        $agency = $this->agencyRepository->getById($agencyId);
        if (!$agency) {
            throw new \Exception("Agency not found with ID: {$agencyId}");
        }

        $agency->update(['is_active' => true]);
        return $agency->fresh();
    }

    /**
     * Deactivate an agency (admin only).
     */
    public function deactivateAgency(int $agencyId): Agency
    {
        $agency = $this->agencyRepository->getById($agencyId);
        if (!$agency) {
            throw new \Exception("Agency not found with ID: {$agencyId}");
        }

        $agency->update(['is_active' => false]);
        return $agency->fresh();
    }

    /**
     * Check if user is an agent with active agency.
     */
    public function isActiveAgent(int $userId): bool
    {
        $agency = $this->getUserAgency($userId);
        return $agency && $agency->is_active;
    }

    /**
     * Check if user has pending agency (registered but not active).
     */
    public function hasPendingAgency(int $userId): bool
    {
        $agency = $this->getUserAgency($userId);
        return $agency && !$agency->is_active;
    }

    /**
     * Delete agency for user.
     */
    public function deleteUserAgency(int $userId): bool
    {
        $agency = $this->getUserAgency($userId);
        if (!$agency) {
            return false;
        }

        DB::beginTransaction();
        try {
            // Delete logo file if exists
            if ($agency->logo) {
                $this->deleteFile($agency->logo);
            }

            // Remove Agent role
            $agency->user->removeRole(RoleEnum::AGENT);
            // Delete agency
            $this->agencyRepository->delete($agency->id);

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Get agencies for admin panel with filters.
     */
    public function getAdminAgencies(?string $status = null, int $perPage = 15)
    {
        return $this->agencyRepository->getWithFiltersForAdmin($status, $perPage);
    }

    /**
     * Update agency status with automatic free subscription assignment.
     */
    public function updateAgencyStatus(int $agencyId, bool $isActive, UserSubscriptionService $subscriptionService): Agency
    {
        DB::beginTransaction();
        try {
            $agency = $this->agencyRepository->getById($agencyId);
            if (!$agency) {
                throw new \Exception("Agency not found with ID: {$agencyId}");
            }

            $wasInactiveBeforeUpdate = !$agency->is_active;

            // Update agency status
            $agency = $this->agencyRepository->updateStatus($agencyId, $isActive);

            // If agency is being activated for the first time, assign free subscription
            if ($isActive && $wasInactiveBeforeUpdate) {
                try {
                    $subscriptionService->assignFreeSubscriptionToUser($agency->user);
                } catch (\Exception $e) {
                    \Log::warning('Failed to assign free subscription to user ' . $agency->user_id . ': ' . $e->getMessage());
                }
            }

            DB::commit();

            return $agency;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
