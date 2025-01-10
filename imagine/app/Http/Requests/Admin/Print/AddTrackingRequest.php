<?php

namespace App\Http\Requests\Admin\Print;

use Illuminate\Foundation\Http\FormRequest;

class AddTrackingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('admin');
    }

    public function rules(): array
    {
        return [
            'tracking_number' => [
                'required',
                'string',
                'max:100',
            ],
            'carrier' => [
                'required',
                'string',
                'in:usps,ups,fedex,dhl',
            ],
            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'carrier.in' => 'The selected carrier is not supported. Valid carriers are: USPS, UPS, FedEx, DHL.',
        ];
    }

    public function attributes(): array
    {
        return [
            'tracking_number' => 'tracking number',
            'carrier' => 'shipping carrier',
            'notes' => 'admin notes',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'carrier' => strtolower($this->carrier),
        ]);
    }
}
