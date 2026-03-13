<?php

namespace App\Repositories;

use App\DTO\AgencyDTO;
use App\Models\Agency;

class AgencyRepository
{
    /**
     * Get agency by user ID.
     */
    public function getByUserId(int $userId): ?Agency
    {
        return Agency::forUser($userId)->first();
    }

    /**
     * Get agency by ID.
     */
    public function getById(int $agencyId): ?Agency
    {
        return Agency::find($agencyId);
    }

    /**
     * Get agency by slug.
     */
    public function getBySlug(string $slug): ?Agency
    {
        return Agency::where('slug', $slug)->first();
    }

    /**
     * Create a new agency from DTO.
     */
    public function create(AgencyDTO $dto): Agency
    {
        return Agency::create($dto->toCreateArray());
    }

    /**
     * Update an existing agency.
     */
    public function update(int $agencyId, AgencyDTO $dto): Agency
    {
        $agency = $this->getById($agencyId);
        if (!$agency) {
            throw new \Exception("Agency not found with ID: {$agencyId}");
        }

        $agency->update($dto->toUpdateArray());
        return $agency->fresh();
    }

    /**
     * Delete an agency.
     */
    public function delete(int $agencyId): bool
    {
        $agency = $this->getById($agencyId);
        if (!$agency) {
            return false;
        }

        return $agency->delete();
    }

    /**
     * Get all active agencies.
     */
    public function getActivePaginated(int $perPage = 15)
    {
        return Agency::active()->paginate($perPage);
    }

    /**
     * Get active agencies paginated with active property count and optional search.
     */
    public function getActivePaginatedWithCounts(int $perPage = 20, ?string $search = null)
    {
        $query = Agency::active()
            ->withCount(['properties' => function ($q) {
                $q->where('status', 'active')->where('active', true);
            }])
            ->with('user')
            ->orderByDesc('created_at');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        return $query->paginate($perPage);
    }

    /**
     * Check if user has an agency.
     */
    public function userHasAgency(int $userId): bool
    {
        return Agency::forUser($userId)->exists();
    }

    /**
     * Get agencies with filters for admin panel.
     */
    public function getWithFiltersForAdmin(?string $status = null, int $perPage = 15)
    {
        $query = Agency::with(['user'])
            ->orderByDesc('created_at');

        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'pending') {
            $query->where('is_active', false);
        }

        return $query->paginate($perPage);
    }

    /**
     * Update agency status (admin operation).
     */
    public function updateStatus(int $agencyId, bool $isActive): Agency
    {
        $agency = $this->getById($agencyId);
        if (!$agency) {
            throw new \Exception("Agency not found with ID: {$agencyId}");
        }

        $agency->update(['is_active' => $isActive]);
        return $agency->fresh();
    }

    /**
     * Check if agency was previously inactive.
     */
    public function wasInactiveBefore(Agency $agency): bool
    {
        return !$agency->is_active;
    }
}
