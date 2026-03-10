<?php

namespace App\Repositories;

use App\DTO\PropertyFilterDTO;
use App\Models\Property;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class PropertyRepository
{
    public function getFeatured(int $limit = 6): Collection
    {
        return Property::active()
            ->featured()
            ->with(['mainImage','images','translations', 'city.translations', 'operationType.translations', 'propertyType.translations', 'currency.translations','user'])
            ->ordered()
            ->limit($limit)
            ->get();
    }

    public function filter(PropertyFilterDTO $dto): LengthAwarePaginator
    {
        $query = Property::active()
              ->with(['mainImage','images','translations', 'city.translations', 'operationType.translations', 'propertyType.translations', 'currency.translations','user']);

        $this->applyFilters($query, $dto);
        $this->applySort($query, $dto->sortBy);

        return $query->paginate($dto->perPage, ['*'], 'page', $dto->page);
    }

    public function findBySlug(string $slug): ?Property
    {
        return Property::where('slug', $slug)
            ->active()
            ->with(['mainImage','images','translations', 'city.translations', 'operationType.translations', 'propertyType.translations', 'currency.translations','user'])
            ->first();
    }

    public function related(Property $property, int $limit = 4): Collection
    {
        return Property::active()
            ->where('id', '!=', $property->id)
            ->where(function (Builder $q) use ($property) {
                $q->where('city_id', $property->city_id)
                  ->orWhere('property_type_id', $property->property_type_id);
            })
           ->with(['mainImage','images','translations', 'city.translations', 'operationType.translations', 'propertyType.translations', 'currency.translations','user'])
            ->ordered()
            ->limit($limit)
            ->get();
    }

    private function applyFilters(Builder $query, PropertyFilterDTO $dto): void
    {
        if ($dto->cityId) {
            $query->where('city_id', $dto->cityId);
        }

        if ($dto->operationTypeId) {
            $query->where('operation_type_id', $dto->operationTypeId);
        }

        if ($dto->propertyTypeId) {
            $query->where('property_type_id', $dto->propertyTypeId);
        }

        if ($dto->priceMin !== null) {
            $query->where('price', '>=', $dto->priceMin);
        }

        if ($dto->priceMax !== null) {
            $query->where('price', '<=', $dto->priceMax);
        }

        if ($dto->areaMin !== null) {
            $query->where('area', '>=', $dto->areaMin);
        }

        if ($dto->areaMax !== null) {
            $query->where('area', '<=', $dto->areaMax);
        }

        if ($dto->rooms !== null) {
            $query->where('rooms', '>=', $dto->rooms);
        }

        if ($dto->query) {
            $safe = $dto->query;
            $query->where(function (Builder $q) use ($safe) {
                $q->whereHas('translations', function (Builder $t) use ($safe) {
                    $t->where('title', 'like', '%' . $safe . '%')
                      ->orWhere('address', 'like', '%' . $safe . '%');
                });
            });
        }
    }

    private function applySort(Builder $query, string $sortBy): void
    {
        match ($sortBy) {
            'price_asc'  => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'area_desc'  => $query->orderByDesc('area'),
            default      => $query->orderByDesc('is_featured')->orderByDesc('created_at'),
        };
    }
}
