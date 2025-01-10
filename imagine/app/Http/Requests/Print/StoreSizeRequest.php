<?php

namespace App\Http\Requests\Print;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSizeRequest extends FormRequest
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
        // Get all valid sizes from config
        $validSizes = collect(config('prints.sizes'))->flatMap(function ($category) {
            return array_keys($category['sizes']);
        })->all();

        return [
            'size' => [
                'required',
                'string',
                Rule::in($validSizes)
            ]
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('size')) {
            $this->merge([
                'size' => trim($this->size)
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
        });
    }
}
