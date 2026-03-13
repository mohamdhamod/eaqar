<?php

namespace App\DTO;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

class PropertyImageDTO
{
    public function __construct(
        public readonly int $propertyId,
        public readonly ?Collection $images = null,
        public readonly ?int $mainImageIndex = 0,
    ) {}

    public static function fromRequest(array $data, int $propertyId): self
    {
        $images = collect($data['images'] ?? [])->filter(fn($img) => $img instanceof UploadedFile);

        return new self(
            propertyId: $propertyId,
            images: $images->isNotEmpty() ? $images : null,
            mainImageIndex: (int) ($data['main_image_index'] ?? 0),
        );
    }
}
