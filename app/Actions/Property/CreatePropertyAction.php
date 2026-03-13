<?php

namespace App\Actions\Property;

use App\DTO\PropertyCreateDTO;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Traits\FileHandler;
use Illuminate\Support\Str;

class CreatePropertyAction
{
    use FileHandler;

    public function execute(PropertyCreateDTO $dto, int $userId): Property
    {
        // Generate unique slug
        $slug = Str::slug($dto->title);
        $originalSlug = $slug;
        $counter = 1;

        while (Property::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        // Create property
        $property = Property::create([
            'user_id'           => $userId,
            'agency_id'         => $dto->agency_id,
            'city_id'           => $dto->city_id,
            'operation_type_id' => $dto->operation_type_id,
            'property_type_id'  => $dto->property_type_id,
            'currency_id'       => $dto->currency_id,
            'slug'              => $slug,
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
            'sort_order'        => Property::max('sort_order') + 1 ?? 1,
        ]);

        // Save translations (based on current language)
        $translationData = [
            'title'       => $dto->title,
            'description' => $dto->description,
            'address'     => $dto->address,
        ];
        $property->translateOrNew()->fill($translationData);
        $property->save();

        // Save images
        if ($dto->images && $dto->images->count() > 0) {
            $this->saveImages($property, $dto->images, $dto->main_image_index);
        }

        return $property->load(['images', 'mainImage', 'translations']);
    }

    private function saveImages(Property $property, $images, int $mainImageIndex): void
    {
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
