<?php

namespace App\Actions\Property;

use App\DTO\PropertyFilterDTO;
use App\Services\PropertyService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetPropertiesAction
{
    public function __construct(
        private readonly PropertyService $service,
    ) {}

    public function execute(PropertyFilterDTO $dto): LengthAwarePaginator
    {
        return $this->service->filterProperties($dto);
    }
}
