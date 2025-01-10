<?php

namespace App\Http\Requests\Print;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuantityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->id === $this->route('order')->user_id;
    }

    private function getQuantityService()
    {
        return app(\App\Services\PrintQuantityService::class);
    }

    public function rules(): array
    {
        $order = $this->route('order');
        $quantity = $this->input('quantity');
        $quantityService = $this->getQuantityService();
        
        // Get the expected price from the quantity service
        $priceData = $quantityService->calculatePrice($order, $quantity);

        return [
            'quantity' => [
                'required',
                'integer',
                'min:1',
                'max:' . $quantityService->getMaxQuantity(),
                function ($attribute, $value, $fail) use ($quantityService) {
                    if (!$quantityService->validateQuantity($value)) {
                        $fail('Invalid quantity selected.');
                    }
                }
            ],
            'final_price' => [
                'required',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) use ($priceData) {
                    if (intval($value) !== intval($priceData['total'])) {
                        \Log::info('Price mismatch', [
                            'submitted' => $value,
                            'calculated' => $priceData['total'],
                            'quantity' => $this->input('quantity')
                        ]);
                        $fail('The submitted price does not match the calculated price.');
                    }
                }
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'quantity.required' => 'Please select a quantity.',
            'quantity.integer' => 'The quantity must be a whole number.',
            'quantity.min' => 'The minimum quantity is 1.',
            'quantity.max' => 'The maximum quantity is ' . $this->getQuantityService()->getMaxQuantity() . '.',
            'final_price.required' => 'The final price is required.',
            'final_price.integer' => 'The final price must be a whole number.',
            'final_price.min' => 'The final price must be greater than zero.'
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        if ($this->wantsJson()) {
            $order = $this->route('order');
            $quantity = $this->input('quantity');
            $priceData = null;
            
            try {
                $priceData = $this->getQuantityService()->calculatePrice($order, $quantity);
            } catch (\Exception $e) {
                \Log::error('Price calculation error: ' . $e->getMessage());
            }

            throw new \Illuminate\Validation\ValidationException($validator, response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'debug' => [
                    'submitted' => [
                        'quantity' => $quantity,
                        'final_price' => $this->input('final_price')
                    ],
                    'calculated' => $priceData
                ]
            ], 422));
        }

        parent::failedValidation($validator);
    }
}
