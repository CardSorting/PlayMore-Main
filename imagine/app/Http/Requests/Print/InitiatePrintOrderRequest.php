<?php

namespace App\Http\Requests\Print;

use Illuminate\Foundation\Http\FormRequest;

class InitiatePrintOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Ensure the gallery belongs to the authenticated user
        return $this->gallery->user_id === auth()->id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // No additional validation needed as gallery is already validated through route model binding
        ];
    }
}
