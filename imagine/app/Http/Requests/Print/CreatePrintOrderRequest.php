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
        $printService = app(PrintOrderService::class);

        return [
            'size' => [
                'required',
                Rule::in(array_keys($printService->getSizes()))
            ],
            'material' => [
                'required',
                Rule::in(array_keys($printService->getMaterials()))
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
            'material.in' => 'Please select a valid print material.',
            'material.required' => 'Please select a print material.',
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
            'material' => 'print material',
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'shipping_zip' => strtoupper($this->shipping_zip),
            'shipping_country' => strtoupper($this->shipping_country),
            'material' => $this->material ?? 'premium_lustre', // Default material
        ]);
    }

    /**
     * Get the validated data from the request.
     *
     * @return array
     */
    public function validated($key = null, $default = null): array
    {
        $validated = parent::validated($key, $default);

        // Calculate the price based on size and material
        $printService = app(PrintOrderService::class);
        $validated['price'] = $printService->calculatePrice(
            $validated['size'],
            $validated['material']
        );

        return $validated;
    }
}
