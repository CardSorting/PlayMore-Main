<?php

namespace App\Http\Requests\Print;

use Illuminate\Foundation\Http\FormRequest;

class TrackPrintRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $order = $this->route('order');
        
        // Must own the order or be admin
        if ($order->user_id !== auth()->id() && !auth()->user()->can('admin')) {
            return false;
        }

        // Check status restrictions
        if ($order->status === 'pending') {
            return false;
        }

        // Check action-specific permission
        return $order->tracking_number && in_array($order->status, ['shipped', 'completed']);
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
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'authorization' => 'Tracking is not available for this order.'
        ];
    }
}
