<?php

namespace App\Actions\Property;

use App\Models\Property;
use App\Services\PropertyService;

class GetPropertyBySlugAction
{
    public function __construct(
        private readonly PropertyService $service,
    ) {}

    public function execute(string $slug): ?Property
    {
        return $this->service->getPropertyBySlug($slug);
    }
}
