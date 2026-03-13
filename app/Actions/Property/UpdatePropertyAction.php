<?php

namespace App\Actions\Property;

use App\DTO\PropertyCreateDTO;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Traits\FileHandler;

class UpdatePropertyAction
{
    use FileHandler;

    public function execute(Property $property, PropertyCreateDTO $dto): Property
    {
        // Update property (slug is immutable after creation to preserve URLs)
        $property->update([
            'city_id'           => $dto->city_id,
            'operation_type_id' => $dto->operation_type_id,
            'property_type_id'  => $dto->property_type_id,
            'currency_id'       => $dto->currency_id,
            'price'             => $dto->price,
            'area'              => $dto->area,
            'rooms'             => $dto->rooms,
            'bathrooms'         => $dto->bathrooms,
            'floor'             => $dto->floor,
            'total_floors'      => $dto->total_floors,
            'building_age'      => $dto->building_age,
            'latitude'          => $dto->latitude,
            'longitude'         => $dto->longitude,
            'is_featured'       => $dto->is_featured,
            'active'            => $dto->active,
        ]);

        // Update translations (based on current language)
        $translationData = [
            'title'       => $dto->title,
            'description' => $dto->description,
            'address'     => $dto->address,
        ];
        $property->translateOrNew()->fill($translationData);
        $property->save();

        // Handle images
        if ($dto->images && $dto->images->count() > 0) {
            $this->updateImages($property, $dto->images, $dto->main_image_index);
        }

        return $property->load(['images', 'mainImage', 'translations']);
    }

    private function updateImages(Property $property, $images, int $mainImageIndex): void
    {
        // Delete existing images
        $property->images()->each(function (PropertyImage $image) {
            $this->deleteFile($image->image);
            $image->delete();
        });

        // Add new images
        $sortOrder = 0;
        foreach ($images as $index => $image) {
            $imagePath = $this->storeFile($image, "properties/{$property->id}");
            $isMain = ($index === $mainImageIndex);

            PropertyImage::create([
                'property_id' => $property->id,
                'image'       => $imagePath,
                'is_main'     => $isMain,
                'sort_order'  => $sortOrder++,
            ]);
        }
    }
}
