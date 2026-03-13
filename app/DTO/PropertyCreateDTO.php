<?php

namespace App\DTO;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

class PropertyCreateDTO
{
    public function __construct(
        public readonly string $title,
        public readonly string $description,
        public readonly string $address,
        public readonly int $city_id,
        public readonly int $operation_type_id,
        public readonly int $property_type_id,
        public readonly int $currency_id,
        public readonly float $price,
        public readonly float $area,
        public readonly int $rooms,
        public readonly int $bathrooms,
        public readonly ?int $floor = null,
        public readonly ?int $total_floors = null,
        public readonly ?int $building_age = null,
        public readonly ?float $latitude = null,
        public readonly ?float $longitude = null,
        public readonly bool $is_featured = false,
        public readonly bool $active = true,
        public readonly ?int $agency_id = null,
        public readonly ?Collection $images = null, // Collection of UploadedFile
        public readonly ?int $main_image_index = 0, // Index of main image
    ) {}

    public static function fromRequest(array $data): self
    {
        $images = collect($data['images'] ?? [])->filter(fn($img) => $img instanceof UploadedFile);
        $mainImageIndex = (int) ($data['main_image_index'] ?? 0);

        return new self(
            title:              $data['title'] ?? '',
            description:        $data['description'] ?? '',
            address:            $data['address'] ?? '',
            city_id:            (int) ($data['city_id'] ?? 0),
            operation_type_id:  (int) ($data['operation_type_id'] ?? 0),
            property_type_id:   (int) ($data['property_type_id'] ?? 0),
            currency_id:        (int) ($data['currency_id'] ?? 0),
            price:              (float) ($data['price'] ?? 0),
            area:               (float) ($data['area'] ?? 0),
            rooms:              (int) ($data['rooms'] ?? 0),
            bathrooms:          (int) ($data['bathrooms'] ?? 0),
            floor:              isset($data['floor']) ? (int) $data['floor'] : null,
            total_floors:       isset($data['total_floors']) ? (int) $data['total_floors'] : null,
            building_age:       isset($data['building_age']) ? (int) $data['building_age'] : null,
            latitude:           isset($data['latitude']) ? (float) $data['latitude'] : null,
            longitude:          isset($data['longitude']) ? (float) $data['longitude'] : null,
            is_featured:        (bool) ($data['is_featured'] ?? false),
            active:             (bool) ($data['active'] ?? true),
            agency_id:          isset($data['agency_id']) ? (int) $data['agency_id'] : null,
            images:             $images,
            main_image_index:   $mainImageIndex,
        );
    }
}

