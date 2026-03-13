<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\RoleEnum;

class AgencyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && (
            auth()->user()->hasRole(RoleEnum::CLIENT) || 
            auth()->user()->hasRole(RoleEnum::AGENT)
        );
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'address' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => trans('translation.agency.validation_name_required'),
            'name.max' => trans('translation.agency.validation_name_max'),
            'logo.image' => trans('translation.agency.validation_logo_image'),
            'logo.mimes' => trans('translation.agency.validation_logo_mimes'),
            'logo.max' => trans('translation.agency.validation_logo_max'),
            'address.max' => trans('translation.agency.validation_address_max'),
        ];
    }

}
