<?php

namespace App\Http\Requests\Admin\Print;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('admin');
    }

    public function rules(): array
    {
        return [
            'status' => [
                'required',
                'string',
                'in:processing,shipped,completed,cancelled'
            ],
            'tracking_number' => [
                'required_if:status,shipped',
                'nullable',
                'string',
                'max:100'
            ],
            'notes' => [
                'nullable',
                'string',
                'max:1000'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'status.in' => 'The selected status is invalid. Valid statuses are: processing, shipped, completed, cancelled.',
            'tracking_number.required_if' => 'A tracking number is required when setting the status to shipped.',
        ];
    }

    public function attributes(): array
    {
        return [
            'status' => 'order status',
            'tracking_number' => 'tracking number',
            'notes' => 'admin notes',
        ];
    }
}
