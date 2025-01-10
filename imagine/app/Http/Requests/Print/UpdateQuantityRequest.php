<?php

namespace App\Http\Requests\Print;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuantityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->id === $this->route('order')->user_id;
    }

    public function rules(): array
    {
        $order = $this->route('order');
        $quantity = $this->input('quantity');
        
        // Get the expected price from the quantity service
        $priceData = app(\App\Services\PrintQuantityService::class)
            ->calculatePrice($order, $quantity);

        return [
            'quantity' => [
                'required',
                'integer',
                'min:1',
                'max:' . config('prints.max_quantity', 10)
            ],
            'final_price' => [
                'required',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) use ($priceData) {
                    if ($value !== $priceData['total']) {
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
            'quantity.max' => 'The maximum quantity is ' . config('prints.max_quantity', 10) . '.',
            'final_price.required' => 'The final price is required.',
            'final_price.integer' => 'The final price must be a whole number.',
            'final_price.min' => 'The final price must be greater than zero.'
        ];
    }
}
