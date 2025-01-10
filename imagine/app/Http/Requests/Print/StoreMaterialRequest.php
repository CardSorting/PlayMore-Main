<?php

namespace App\Http\Requests\Print;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMaterialRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'material' => [
                'required',
                'string',
                Rule::in(array_keys(config('prints.materials'))),
            ]
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('material')) {
            $this->merge([
                'material' => trim($this->material)
            ]);
        }
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Ensure gallery belongs to current user
            $gallery = $this->route('gallery');
            if ($gallery->user_id !== auth()->id()) {
                $validator->errors()->add('gallery', 'You do not have permission to create prints from this gallery.');
            }

            // Ensure size is selected in session
            if (!session()->has('print_order.size')) {
                $validator->errors()->add('size', 'Please select a size first.');
            }
        });
    }
}
