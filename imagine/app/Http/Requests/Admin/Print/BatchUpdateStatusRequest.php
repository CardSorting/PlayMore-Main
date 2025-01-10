<?php

namespace App\Http\Requests\Admin\Print;

use App\Models\PrintOrder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class BatchUpdateStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('admin') && 
               Gate::check('batch-update-orders', [PrintOrder::class]);
    }

    public function rules(): array
    {
        return [
            'orders' => [
                'required',
                'array',
                'min:1',
                'max:100', // Limit batch size for performance
            ],
            'orders.*' => [
                'required',
                'exists:print_orders,id',
            ],
            'status' => [
                'required',
                'string',
                'in:processing,shipped,completed,cancelled',
            ],
            'notify_customers' => [
                'sometimes',
                'boolean',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'orders.max' => 'You can only update up to 100 orders at once.',
            'status.in' => 'The selected status is invalid. Valid statuses are: processing, shipped, completed, cancelled.',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Verify all orders can be updated to the requested status
            $orders = PrintOrder::whereIn('id', $this->orders)->get();
            
            foreach ($orders as $order) {
                if ($this->status === 'cancelled' && !in_array($order->status, ['pending', 'processing'])) {
                    $validator->errors()->add(
                        'orders', 
                        "Order #{$order->order_number} cannot be cancelled in its current state."
                    );
                }

                if ($this->status === 'shipped' && !$order->tracking_number) {
                    $validator->errors()->add(
                        'orders', 
                        "Order #{$order->order_number} requires tracking information before it can be marked as shipped."
                    );
                }

                if ($this->status === 'completed' && $order->status !== 'shipped') {
                    $validator->errors()->add(
                        'orders', 
                        "Order #{$order->order_number} must be shipped before it can be marked as completed."
                    );
                }
            }
        });
    }

    public function validated($key = null, $default = null): array
    {
        $validated = parent::validated();
        
        // Default notify_customers to true if not provided
        $validated['notify_customers'] = $validated['notify_customers'] ?? true;

        return $validated;
    }
}
