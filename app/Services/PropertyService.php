<?php

namespace App\Services;

use App\Actions\Property\CreatePropertyAction;
use App\Actions\Property\DeletePropertyAction;
use App\Actions\Property\UpdatePropertyAction;
use App\DTO\PropertyCreateDTO;
use App\DTO\PropertyFilterDTO;
use App\Enums\ConfigurationsTypeEnum;
use App\Models\Agency;
use App\Models\Configuration;
use App\Models\Property;
use App\Repositories\PropertyRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class PropertyService
{
    public function __construct(
        private readonly PropertyRepository $repository,
        private readonly CreatePropertyAction $createPropertyAction,
        private readonly UpdatePropertyAction $updatePropertyAction,
        private readonly DeletePropertyAction $deletePropertyAction,
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

    public function createProperty(PropertyCreateDTO $dto, int $userId): Property
    {
        return $this->createPropertyAction->execute($dto, $userId);
    }

    public function updateProperty(Property $property, PropertyCreateDTO $dto): Property
    {
        return $this->updatePropertyAction->execute($property, $dto);
    }

    public function deleteProperty(Property $property): bool
    {
        return $this->deletePropertyAction->execute($property);
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
            'agencies' => Agency::where('is_active', true)
                ->select('id', 'name')
                ->orderBy('name')
                ->get(),
            'currencies' => Configuration::where('key', ConfigurationsTypeEnum::CURRENCY)
                ->where('active', true)
                ->withTranslation()
                ->get(),
        ];
    }
}
