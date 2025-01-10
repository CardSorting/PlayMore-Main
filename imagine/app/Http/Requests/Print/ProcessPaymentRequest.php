<?php

namespace App\Http\Requests\Print;

use App\Models\PrintOrder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProcessPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        $order = $this->route('order');
        return $order && $order->user_id === auth()->id() && $order->status === 'pending';
    }

    public function rules(): array
    {
        return [
            'payment_intent_id' => ['required', 'string'],
            'save_payment_method' => ['boolean'],
            'billing_email' => ['required', 'email'],
            'billing_name' => ['required', 'string', 'max:255'],
            'billing_address' => ['required', 'string', 'max:255'],
            'billing_city' => ['required', 'string', 'max:255'],
            'billing_state' => ['required', 'string', 'max:255'],
            'billing_zip' => ['required', 'string', 'max:20'],
            'billing_country' => [
                'required',
                'string',
                Rule::in(['US', 'CA', 'GB', 'AU']),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'payment_intent_id.required' => 'Payment intent information is required.',
            'billing_country.in' => 'We currently only accept billing addresses from the United States, Canada, United Kingdom, and Australia.',
        ];
    }

    public function attributes(): array
    {
        return [
            'billing_email' => 'email address',
            'billing_name' => 'full name',
            'billing_address' => 'street address',
            'billing_city' => 'city',
            'billing_state' => 'state/province',
            'billing_zip' => 'ZIP/postal code',
            'billing_country' => 'country',
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'billing_zip' => strtoupper($this->billing_zip),
            'billing_country' => strtoupper($this->billing_country),
            'save_payment_method' => $this->boolean('save_payment_method'),
        ]);
    }

    public function getBillingDetails(): array
    {
        return [
            'email' => $this->billing_email,
            'name' => $this->billing_name,
            'address' => [
                'line1' => $this->billing_address,
                'city' => $this->billing_city,
                'state' => $this->billing_state,
                'postal_code' => $this->billing_zip,
                'country' => $this->billing_country,
            ],
        ];
    }
}
