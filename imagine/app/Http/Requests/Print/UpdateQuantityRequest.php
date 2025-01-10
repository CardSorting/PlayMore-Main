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
        return [
            'quantity' => [
                'required',
                'integer',
                'min:1',
                'max:' . config('prints.max_quantity', 10)
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'quantity.required' => 'Please select a quantity.',
            'quantity.integer' => 'The quantity must be a whole number.',
            'quantity.min' => 'The minimum quantity is 1.',
            'quantity.max' => 'The maximum quantity is ' . config('prints.max_quantity', 10) . '.'
        ];
    }
}
