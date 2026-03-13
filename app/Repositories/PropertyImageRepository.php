<?php

namespace App\Repositories;

use App\Models\Property;
use App\Models\PropertyImage;
use Illuminate\Database\Eloquent\Collection;

class PropertyImageRepository
{
    public function getByProperty(int $propertyId): Collection
    {
        return PropertyImage::where('property_id', $propertyId)
            ->orderByDesc('is_main')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }

    public function find(int $id): ?PropertyImage
    {
        return PropertyImage::find($id);
    }

    public function create(array $data): PropertyImage
    {
        return PropertyImage::create($data);
    }

    public function update(PropertyImage $image, array $data): bool
    {
        return $image->update($data);
    }

    public function delete(PropertyImage $image): bool
    {
        return $image->delete();
    }

    public function countByProperty(int $propertyId): int
    {
        return PropertyImage::where('property_id', $propertyId)->count();
    }

    public function clearMainByProperty(int $propertyId, ?int $exceptId = null): int
    {
        $query = PropertyImage::where('property_id', $propertyId)
            ->where('is_main', true);

        if ($exceptId) {
            $query->where('id', '!=', $exceptId);
        }

        return $query->update(['is_main' => false]);
    }

    public function setMain(PropertyImage $image): bool
    {
        $this->clearMainByProperty($image->property_id, $image->id);

        return $image->update(['is_main' => true]);
    }

    public function belongsToProperty(PropertyImage $image, int $propertyId): bool
    {
        return (int) $image->property_id === $propertyId;
    }
}
