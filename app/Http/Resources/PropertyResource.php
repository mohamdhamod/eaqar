<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Property
 */
class PropertyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'slug'            => $this->slug,
            'title'           => $this->title,
            'description'     => $this->description,
            'address'         => $this->address,
            'price'           => (float) $this->price,
            'price_formatted' => number_format((float) $this->price, 0, '.', ','),
            'area'            => $this->area ? (float) $this->area : null,
            'rooms'           => $this->rooms,
            'bathrooms'       => $this->bathrooms,
            'floor'           => $this->floor,
            'total_floors'    => $this->total_floors,
            'building_age'    => $this->building_age,
            'latitude'        => $this->latitude ? (float) $this->latitude : null,
            'longitude'       => $this->longitude ? (float) $this->longitude : null,
            'status'          => $this->status,
            'is_featured'     => (bool) $this->is_featured,
            'city'            => $this->whenLoaded('city', fn () => [
                'id'   => $this->city->id,
                'name' => $this->city->name,
                'code' => $this->city->code,
            ]),
            'operation_type'  => $this->whenLoaded('operationType', fn () => [
                'id'   => $this->operationType->id,
                'name' => $this->operationType->name,
                'code' => $this->operationType->code,
            ]),
            'property_type'   => $this->whenLoaded('propertyType', fn () => [
                'id'   => $this->propertyType->id,
                'name' => $this->propertyType->name,
                'code' => $this->propertyType->code,
            ]),
            'currency'        => $this->whenLoaded('currency', fn () => [
                'id'   => $this->currency->id,
                'name' => $this->currency->name,
                'code' => $this->currency->code,
            ]),
            'main_image'      => $this->whenLoaded('mainImage', fn () => $this->mainImage?->image),
            'images'          => $this->whenLoaded('images', fn () => $this->images->pluck('image')),
            'url'             => route('properties.show', $this->slug),
            'created_at'      => $this->created_at?->diffForHumans(),
        ];
    }
}
