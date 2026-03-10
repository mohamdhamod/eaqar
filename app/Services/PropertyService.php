<?php

namespace App\Services;

use App\DTO\PropertyFilterDTO;
use App\Enums\ConfigurationsTypeEnum;
use App\Models\Configuration;
use App\Models\Property;
use App\Repositories\PropertyRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class PropertyService
{
    public function __construct(
        private readonly PropertyRepository $repository,
    ) {}

    public function getFeaturedProperties(int $limit = 6): Collection
    {
        return $this->repository->getFeatured($limit);
    }

    public function filterProperties(PropertyFilterDTO $dto): LengthAwarePaginator
    {
        return $this->repository->filter($dto);
    }

    public function getPropertyBySlug(string $slug): ?Property
    {
        return $this->repository->findBySlug($slug);
    }

    public function getRelatedProperties(Property $property, int $limit = 4): Collection
    {
        return $this->repository->related($property, $limit);
    }

    public function getFilterOptions(): array
    {
        return [
            'cities' => Configuration::where('key', ConfigurationsTypeEnum::CITY)
                ->where('active', true)
                ->withTranslation()
                ->get(),
            'operation_types' => Configuration::where('key', ConfigurationsTypeEnum::OPERATION_TYPE)
                ->where('active', true)
                ->withTranslation()
                ->get(),
            'property_types' => Configuration::where('key', ConfigurationsTypeEnum::PROPERTY_TYPE)
                ->where('active', true)
                ->withTranslation()
                ->get(),
        ];
    }
}
