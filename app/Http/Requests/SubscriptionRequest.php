<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubscriptionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'key' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-z_]+$/',
                Rule::unique('subscriptions', 'key')
                    ->ignore($this->route('subscription')),
            ],
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'price' => ['required', 'numeric', 'min:0', 'max:99999.99'],
            'currency_id' => ['required', 'exists:configurations,id'],
            'duration_days' => ['required', 'integer', 'min:1', 'max:36500'],
            'max_properties' => ['required', 'integer', 'min:0', 'max:99999'],
            'icon' => ['nullable', 'string', 'max:50'],
            'color' => ['nullable', 'string', 'max:20', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'key.required' => __('translation.subscription.validation.key_required'),
            'key.unique' => __('translation.subscription.validation.key_unique'),
            'key.regex' => __('translation.subscription.validation.key_format'),
            'name.required' => __('translation.subscription.validation.name_required'),
            'price.required' => __('translation.subscription.validation.price_required'),
            'price.numeric' => __('translation.subscription.validation.price_numeric'),
            'currency_id.required' => __('translation.subscription.validation.currency_required'),
            'currency_id.exists' => __('translation.subscription.validation.currency_exists'),
            'duration_days.required' => __('translation.subscription.validation.duration_required'),
            'duration_days.integer' => __('translation.subscription.validation.duration_integer'),
            'max_properties.required' => __('translation.subscription.validation.max_properties_required'),
            'max_properties.integer' => __('translation.subscription.validation.max_properties_integer'),
            'color.regex' => __('translation.subscription.validation.color_format'),
        ];
    }
}
