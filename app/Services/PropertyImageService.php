<?php

namespace App\Services;

use App\Actions\PropertyImage\DeletePropertyImageAction;
use App\Actions\PropertyImage\SetMainImageAction;
use App\Actions\PropertyImage\StorePropertyImagesAction;
use App\DTO\PropertyImageDTO;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Repositories\PropertyImageRepository;
use Illuminate\Database\Eloquent\Collection;

class PropertyImageService
{
    public function __construct(
        private readonly PropertyImageRepository $repository,
        private readonly StorePropertyImagesAction $storeAction,
        private readonly SetMainImageAction $setMainAction,
        private readonly DeletePropertyImageAction $deleteAction,
    ) {}

    public function getByProperty(Property $property): Collection
    {
        return $this->repository->getByProperty($property->id);
    }

    /**
     * @return Collection<int, PropertyImage>
     */
    public function store(PropertyImageDTO $dto): Collection
    {
        return $this->storeAction->execute($dto);
    }

    public function setMain(Property $property, PropertyImage $image): array
    {
        $this->ensureOwnership($property, $image);

        $this->setMainAction->execute($image);

        return [
            'success' => true,
            'message' => __('translation.property.main_image_updated'),
        ];
    }

    public function delete(Property $property, PropertyImage $image): array
    {
        $this->ensureOwnership($property, $image);

        $count = $this->repository->countByProperty($property->id);
        if ($count <= 1) {
            return [
                'success' => false,
                'message' => __('translation.property.cannot_delete_last_image'),
            ];
        }

        $this->deleteAction->execute($image);

        return [
            'success' => true,
            'message' => __('translation.general.deleted_successfully'),
        ];
    }

    private function ensureOwnership(Property $property, PropertyImage $image): void
    {
        abort_if(
            !$this->repository->belongsToProperty($image, $property->id),
            403,
            'Image does not belong to this property'
        );
    }
}
