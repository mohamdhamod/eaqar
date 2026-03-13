<?php

namespace App\Actions;

use App\Services\AgencyService;
use Illuminate\Pagination\LengthAwarePaginator;

class GetAgenciesAction
{
    public function __construct(
        private readonly AgencyService $agencyService,
    ) {}

    /**
     * Get paginated public agencies with property counts.
     */
    public function handle(int $perPage = 20, ?string $search = null): LengthAwarePaginator
    {
        return $this->agencyService->getPublicAgencies($perPage, $search);
    }
}
