<?php

namespace App\Http\Requests\Print;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreShippingAddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->route('order')->user_id === auth()->id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'shipping_name' => ['required', 'string', 'max:255'],
            'shipping_address' => ['required', 'string', 'max:255'],
            'shipping_city' => ['required', 'string', 'max:255'],
            'shipping_state' => ['required', 'string', 'max:255'],
            'shipping_zip' => ['required', 'string', 'max:20'],
            'shipping_country' => ['required', 'string', Rule::in(['US', 'CA'])] // Example: Only US and Canada
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'shipping_name' => trim($this->shipping_name),
            'shipping_address' => trim($this->shipping_address),
            'shipping_city' => trim($this->shipping_city),
            'shipping_state' => trim($this->shipping_state),
            'shipping_zip' => trim($this->shipping_zip),
            'shipping_country' => trim($this->shipping_country),
        ]);
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'shipping_country.in' => 'We currently only ship to the United States and Canada.'
        ];
    }
}
