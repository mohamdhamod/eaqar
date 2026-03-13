<?php

namespace App\Actions\PropertyImage;

use App\DTO\PropertyImageDTO;
use App\Models\PropertyImage;
use App\Repositories\PropertyImageRepository;
use App\Traits\FileHandler;
use Illuminate\Support\Collection;

class StorePropertyImagesAction
{
    use FileHandler;

    public function __construct(
        private readonly PropertyImageRepository $repository,
    ) {}

    /**
     * @return Collection<int, PropertyImage>
     */
    public function execute(PropertyImageDTO $dto): Collection
    {
        $stored = collect();

        if (!$dto->images || $dto->images->isEmpty()) {
            return $stored;
        }

        foreach ($dto->images as $idx => $file) {
            $path = $this->storeFile($file, "properties/{$dto->propertyId}");

            $image = $this->repository->create([
                'property_id' => $dto->propertyId,
                'image'       => $path,
                'is_main'     => $idx === $dto->mainImageIndex,
                'sort_order'  => $idx,
            ]);

            $stored->push($image);
        }

        // Ensure only one main image
        if ($dto->mainImageIndex !== null && $stored->has($dto->mainImageIndex)) {
            $mainImage = $stored->get($dto->mainImageIndex);
            $this->repository->clearMainByProperty($dto->propertyId, $mainImage->id);
        }

        return $stored;
    }
}
