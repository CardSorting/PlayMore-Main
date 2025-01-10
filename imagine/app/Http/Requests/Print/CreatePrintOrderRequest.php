<?php

namespace App\Http\Requests\Print;

use App\Services\PrintOrderService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreatePrintOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'size' => [
                'required',
                Rule::in(array_keys(app(PrintOrderService::class)->getSizes()))
            ],
            'shipping_name' => ['required', 'string', 'max:255'],
            'shipping_address' => ['required', 'string', 'max:255'],
            'shipping_city' => ['required', 'string', 'max:255'],
            'shipping_state' => ['required', 'string', 'max:255'],
            'shipping_zip' => ['required', 'string', 'max:20'],
            'shipping_country' => [
                'required',
                'string',
                Rule::in(['US', 'CA', 'GB', 'AU']), // Supported countries
            ],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'size.in' => 'Please select a valid print size.',
            'shipping_country.in' => 'We currently only ship to the United States, Canada, United Kingdom, and Australia.',
        ];
    }

    public function attributes(): array
    {
        return [
            'shipping_name' => 'full name',
            'shipping_address' => 'street address',
            'shipping_city' => 'city',
            'shipping_state' => 'state/province',
            'shipping_zip' => 'ZIP/postal code',
            'shipping_country' => 'country',
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'shipping_zip' => strtoupper($this->shipping_zip),
            'shipping_country' => strtoupper($this->shipping_country),
        ]);
    }
}
