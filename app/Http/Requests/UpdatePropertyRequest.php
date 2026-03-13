<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePropertyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'title'               => 'required|string|max:255',
            'description'         => 'required|string',
            'address'             => 'required|string|max:255',
            'city_id'             => 'required|integer|exists:configurations,id',
            'operation_type_id'   => 'required|integer|exists:configurations,id',
            'property_type_id'    => 'required|integer|exists:configurations,id',
            'currency_id'         => 'required|integer|exists:configurations,id',
            'price'               => 'required|numeric|min:0',
            'area'                => 'required|numeric|min:0',
            'rooms'               => 'required|integer|min:0',
            'bathrooms'           => 'required|integer|min:0',
            'floor'               => 'nullable|integer',
            'total_floors'        => 'nullable|integer',
            'building_age'        => 'nullable|integer',
            'latitude'            => 'nullable|numeric|between:-90,90',
            'longitude'           => 'nullable|numeric|between:-180,180',
            'is_featured'         => 'boolean',
            'active'              => 'boolean',
            'images'              => 'nullable|array',
            'images.*'            => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'main_image_index'    => 'nullable|integer|min:0',
            'agency_id'           => 'nullable|integer|exists:agencies,id',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'            => __('validation.required', ['attribute' => __('translation.general.title')]),
            'title.string'              => __('validation.string', ['attribute' => __('translation.general.title')]),
            'title.max'                 => __('validation.max.string', ['attribute' => __('translation.general.title'), 'max' => 255]),
            
            'description.required'      => __('validation.required', ['attribute' => __('translation.general.description')]),
            'description.string'        => __('validation.string', ['attribute' => __('translation.general.description')]),
            
            'address.required'          => __('validation.required', ['attribute' => __('translation.general.address')]),
            'address.string'            => __('validation.string', ['attribute' => __('translation.general.address')]),
            
            'city_id.required'          => __('validation.required', ['attribute' => __('translation.property.city')]),
            'city_id.exists'            => __('validation.exists', ['attribute' => __('translation.property.city')]),
            
            'operation_type_id.required' => __('validation.required', ['attribute' => __('translation.property.operation')]),
            'operation_type_id.exists' => __('validation.exists', ['attribute' => __('translation.property.operation')]),
            
            'property_type_id.required' => __('validation.required', ['attribute' => __('translation.property.type')]),
            'property_type_id.exists'   => __('validation.exists', ['attribute' => __('translation.property.type')]),
            
            'currency_id.required'      => __('validation.required', ['attribute' => __('translation.property.currency')]),
            'currency_id.exists'        => __('validation.exists', ['attribute' => __('translation.property.currency')]),
            
            'price.required'            => __('validation.required', ['attribute' => __('translation.property.price')]),
            'price.numeric'             => __('validation.numeric', ['attribute' => __('translation.property.price')]),
            'price.min'                 => __('validation.min.numeric', ['attribute' => __('translation.property.price'), 'min' => 0]),
            
            'area.required'             => __('validation.required', ['attribute' => __('translation.property.area')]),
            'area.numeric'              => __('validation.numeric', ['attribute' => __('translation.property.area')]),
            'area.min'                  => __('validation.min.numeric', ['attribute' => __('translation.property.area'), 'min' => 0]),
            
            'rooms.required'            => __('validation.required', ['attribute' => __('translation.property.rooms')]),
            'rooms.integer'             => __('validation.integer', ['attribute' => __('translation.property.rooms')]),
            'rooms.min'                 => __('validation.min.numeric', ['attribute' => __('translation.property.rooms'), 'min' => 0]),
            
            'bathrooms.required'        => __('validation.required', ['attribute' => __('translation.property.bathrooms')]),
            'bathrooms.integer'         => __('validation.integer', ['attribute' => __('translation.property.bathrooms')]),
            'bathrooms.min'             => __('validation.min.numeric', ['attribute' => __('translation.property.bathrooms'), 'min' => 0]),
            
            'images.*.image'            => __('validation.image', ['attribute' => __('translation.property.property_images')]),
            'images.*.mimes'            => __('validation.mimes', ['attribute' => __('translation.property.property_images'), 'values' => 'jpeg,png,jpg,gif,webp']),
            'images.*.max'              => __('validation.max.file', ['attribute' => __('translation.property.property_images'), 'max' => '5120']),
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'is_featured' => (bool) $this->is_featured,
            'active'      => (bool) $this->active,
            'agency_id'   => $this->agency_id ?? $this->user()->agency?->id,
        ]);
    }
}
