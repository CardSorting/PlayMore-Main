<?php

namespace App\Http\Requests\Print;

use Illuminate\Foundation\Http\FormRequest;

class SelectMaterialRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $gallery = $this->route('gallery');
        return $gallery->user_id === auth()->id() && session()->has('print_order.size');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * Handle a failed authorization attempt.
     */
    protected function failedAuthorization()
    {
        if (!session()->has('print_order.size')) {
            redirect()
                ->route('prints.select-size', ['gallery' => $this->route('gallery')])
                ->withErrors(['size' => 'Please select a size first'])
                ->throwResponse();
        }
        parent::failedAuthorization();
    }
}
