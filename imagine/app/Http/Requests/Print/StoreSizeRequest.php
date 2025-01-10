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
        return [
            'size' => [
                'required',
                'string',
                Rule::in(array_keys(config('prints.sizes'))),
            ],
            'gallery_id' => [
                'required',
                'exists:galleries,id',
                function ($attribute, $value, $fail) {
                    $gallery = $this->route('gallery');
                    if ($gallery->id != $value) {
                        $fail('Invalid gallery selected.');
                    }
                    if ($gallery->user_id !== auth()->id()) {
                        $fail('You do not have permission to create prints from this gallery.');
                    }
                },
            ],
        ];
    }
}
