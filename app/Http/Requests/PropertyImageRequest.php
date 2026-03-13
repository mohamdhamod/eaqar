<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PropertyImageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'images'       => 'required|array|min:1',
            'images.*'     => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
            'main_image_index' => 'nullable|integer|min:0',
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'images.required'   => __('translation.validation.images_required'),
            'images.array'      => __('translation.validation.images_must_be_array'),
            'images.min'        => __('translation.validation.at_least_one_image'),
            'images.*.required' => __('translation.validation.image_required'),
            'images.*.image'    => __('translation.validation.image_must_be_image'),
            'images.*.mimes'    => __('translation.validation.images_unsupported_format'),
            'images.*.max'      => __('translation.validation.images_max_size'),
        ];
    }
}
