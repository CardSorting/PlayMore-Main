<?php

namespace App\Http\Requests\Print;

use Illuminate\Foundation\Http\FormRequest;

class ShowSuccessRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $order = $this->route('order');
        return $order->user_id === auth()->id() && $order->isPaid();
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
        $order = $this->route('order');
        if (!$order->isPaid()) {
            redirect()->route('prints.checkout', ['order' => $order])->throwResponse();
        }
        parent::failedAuthorization();
    }
}
