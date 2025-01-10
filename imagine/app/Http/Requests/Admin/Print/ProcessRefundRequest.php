<?php

namespace App\Http\Requests\Admin\Print;

use App\Models\PrintOrder;
use Illuminate\Foundation\Http\FormRequest;

class ProcessRefundRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('admin');
    }

    public function rules(): array
    {
        /** @var PrintOrder $order */
        $order = $this->route('order');

        return [
            'amount' => [
                'required',
                'numeric',
                'min:0.01',
                'max:' . ($order->price - ($order->refunded_amount ?? 0)),
            ],
            'reason' => [
                'required',
                'string',
                'max:1000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'amount.max' => 'The refund amount cannot exceed the remaining refundable amount.',
            'amount.min' => 'The refund amount must be at least $0.01.',
        ];
    }

    public function attributes(): array
    {
        return [
            'amount' => 'refund amount',
            'reason' => 'refund reason',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('amount') && is_string($this->amount)) {
            $this->merge([
                'amount' => (float) str_replace(['$', ','], '', $this->amount),
            ]);
        }
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            /** @var PrintOrder $order */
            $order = $this->route('order');

            if (!$order->paid_at) {
                $validator->errors()->add('amount', 'Cannot refund an unpaid order.');
            }

            if ($order->status === 'cancelled') {
                $validator->errors()->add('amount', 'Cannot refund a cancelled order.');
            }

            if ($order->refunded_at && !$this->user()->can('process-multiple-refunds')) {
                $validator->errors()->add('amount', 'This order has already been refunded.');
            }
        });
    }
}
