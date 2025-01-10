<?php

namespace App\Http\Requests\Admin\Print;

use App\Models\PrintOrder;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class BatchExportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('admin') && 
               Gate::check('export-orders', [PrintOrder::class]);
    }

    public function rules(): array
    {
        return [
            'format' => [
                'required',
                'string',
                'in:csv,xlsx',
            ],
            'status' => [
                'nullable',
                'string',
                'in:pending,processing,shipped,completed,cancelled',
            ],
            'date_from' => [
                'nullable',
                'date',
                'before_or_equal:today',
            ],
            'date_to' => [
                'nullable',
                'date',
                'after_or_equal:date_from',
                'before_or_equal:today',
            ],
            'include_fields' => [
                'sometimes',
                'array',
            ],
            'include_fields.*' => [
                'required',
                'string',
                'in:order_number,status,created_at,paid_at,shipped_at,completed_at,cancelled_at,' .
                   'shipping_name,shipping_address,shipping_city,shipping_state,shipping_zip,shipping_country,' .
                   'tracking_number,shipping_carrier,price,refunded_amount,size',
            ],
            'notify_when_ready' => [
                'sometimes',
                'boolean',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'format.in' => 'The selected export format is invalid. Valid formats are: CSV, XLSX.',
            'status.in' => 'The selected status filter is invalid.',
            'date_from.before_or_equal' => 'The start date cannot be in the future.',
            'date_to.before_or_equal' => 'The end date cannot be in the future.',
            'date_to.after_or_equal' => 'The end date must be after or equal to the start date.',
            'include_fields.*.in' => 'One or more selected fields are invalid.',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Convert date strings to proper format if provided
        if ($this->has('date_from') && is_string($this->date_from)) {
            $this->merge([
                'date_from' => Carbon::parse($this->date_from)->startOfDay(),
            ]);
        }

        if ($this->has('date_to') && is_string($this->date_to)) {
            $this->merge([
                'date_to' => Carbon::parse($this->date_to)->endOfDay(),
            ]);
        }

        // Default to all fields if none specified
        if (!$this->has('include_fields')) {
            $this->merge([
                'include_fields' => [
                    'order_number',
                    'status',
                    'created_at',
                    'paid_at',
                    'shipped_at',
                    'completed_at',
                    'shipping_name',
                    'shipping_address',
                    'shipping_city',
                    'shipping_state',
                    'shipping_zip',
                    'shipping_country',
                    'tracking_number',
                    'price',
                    'size',
                ],
            ]);
        }

        // Default notify_when_ready to true
        $this->merge([
            'notify_when_ready' => $this->notify_when_ready ?? true,
        ]);
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Verify there are orders within the date range if specified
            if ($this->date_from && $this->date_to) {
                $count = PrintOrder::query()
                    ->when($this->status, fn($q) => $q->where('status', $this->status))
                    ->whereBetween('created_at', [$this->date_from, $this->date_to])
                    ->count();

                if ($count === 0) {
                    $validator->errors()->add(
                        'date_range',
                        'No orders found within the specified date range.'
                    );
                }

                // Warn if trying to export too many orders
                if ($count > 10000) {
                    $validator->errors()->add(
                        'date_range',
                        'The selected date range would export too many orders. Please select a smaller range.'
                    );
                }
            }
        });
    }

    public function validated($key = null, $default = null): array
    {
        $validated = parent::validated();

        // Add export filename
        $validated['filename'] = $this->generateExportFilename();

        return $validated;
    }

    protected function generateExportFilename(): string
    {
        $parts = ['print_orders'];

        if ($this->status) {
            $parts[] = strtolower($this->status);
        }

        if ($this->date_from && $this->date_to) {
            $parts[] = $this->date_from->format('Y-m-d');
            $parts[] = $this->date_to->format('Y-m-d');
        }

        $parts[] = now()->format('Y-m-d-His');

        return implode('_', $parts) . '.' . $this->format;
    }
}
