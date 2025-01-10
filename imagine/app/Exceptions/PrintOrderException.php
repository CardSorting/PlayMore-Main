<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;

class PrintOrderException extends Exception
{
    /**
     * The error details.
     *
     * @var array<string, array<string>>
     */
    protected array $errors = [];

    /**
     * The HTTP status code.
     */
    protected int $statusCode = Response::HTTP_UNPROCESSABLE_ENTITY;

    /**
     * Create a new exception instance.
     */
    public function __construct(
        string $message = '',
        array $errors = [],
        int $code = 0,
        ?Exception $previous = null,
        ?int $statusCode = null
    ) {
        parent::__construct($message, $code, $previous);

        $this->errors = $this->formatErrors($errors);
        
        if ($statusCode !== null) {
            $this->statusCode = $statusCode;
        }
    }

    /**
     * Static constructors for common error cases.
     */
    public static function invalidConfiguration(string $reason): self
    {
        return new static(
            "Invalid configuration: {$reason}",
            ['configuration' => ["The configuration is invalid: {$reason}"]],
            0,
            null,
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }

    public static function invalidSize(string $size): self
    {
        return new static(
            "Invalid print size: {$size}",
            ['size' => ["The size '{$size}' is not available."]]
        );
    }

    public static function invalidMaterial(string $material): self
    {
        return new static(
            "Invalid print material: {$material}",
            ['material' => ["The material '{$material}' is not available."]]
        );
    }

    public static function materialUnavailable(string $material, string $size): self
    {
        return new static(
            "Material not available for size: {$material} ({$size})",
            ['material' => ["The material '{$material}' is not available for size {$size}."]]
        );
    }

    public static function invalidStatus(string $status): self
    {
        return new static(
            "Invalid order status: {$status}",
            ['status' => ["The status '{$status}' is not valid."]]
        );
    }

    public static function invalidShippingCountry(string $country): self
    {
        return new static(
            "Invalid shipping country: {$country}",
            ['shipping_country' => ["We do not ship to {$country}."]]
        );
    }

    public static function orderNotCancellable(string $reason): self
    {
        return new static(
            "Order cannot be cancelled: {$reason}",
            ['order' => ["This order cannot be cancelled: {$reason}"]],
            0,
            null,
            Response::HTTP_FORBIDDEN
        );
    }

    public static function orderNotRefundable(string $reason): self
    {
        return new static(
            "Order cannot be refunded: {$reason}",
            ['order' => ["This order cannot be refunded: {$reason}"]],
            0,
            null,
            Response::HTTP_FORBIDDEN
        );
    }

    public static function insufficientStock(string $size, ?string $material = null): self
    {
        $message = "The selected size {$size}";
        if ($material) {
            $message .= " with {$material} material";
        }
        $message .= " is temporarily out of stock.";

        return new static(
            "Insufficient stock for size: {$size}" . ($material ? " ({$material})" : ""),
            ['size' => [$message]],
            0,
            null,
            Response::HTTP_CONFLICT
        );
    }

    public static function productionError(string $reason): self
    {
        return new static(
            "Production error: {$reason}",
            ['production' => ["An error occurred during production: {$reason}"]],
            0,
            null,
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }

    public static function shippingError(string $reason): self
    {
        return new static(
            "Shipping error: {$reason}",
            ['shipping' => ["An error occurred with shipping: {$reason}"]],
            0,
            null,
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }

    public static function invalidTrackingNumber(string $number): self
    {
        return new static(
            "Invalid tracking number: {$number}",
            ['tracking_number' => ["The tracking number {$number} is invalid."]]
        );
    }

    public static function orderAlreadyPaid(): self
    {
        return new static(
            'Order has already been paid',
            ['order' => ['This order has already been paid for.']],
            0,
            null,
            Response::HTTP_CONFLICT
        );
    }

    /**
     * Format the error messages.
     *
     * @param array<string, string|array<string>> $errors
     * @return array<string, array<string>>
     */
    protected function formatErrors(array $errors): array
    {
        return collect($errors)->map(function ($messages, $field) {
            return Arr::wrap($messages);
        })->all();
    }

    /**
     * Get the error messages.
     *
     * @return array<string, array<string>>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Get the HTTP status code.
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Render the exception into an HTTP response.
     */
    public function render($request): Response
    {
        $response = [
            'message' => $this->getMessage(),
            'errors' => $this->getErrors(),
        ];

        if (config('app.debug')) {
            $response['debug'] = [
                'exception' => get_class($this),
                'file' => $this->getFile(),
                'line' => $this->getLine(),
                'trace' => collect($this->getTrace())
                    ->map(fn($trace) => Arr::except($trace, ['args']))
                    ->all(),
            ];
        }

        return new Response(
            $response,
            $this->getStatusCode(),
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * Report the exception.
     */
    public function report(): void
    {
        // Log critical errors
        if ($this->statusCode >= 500) {
            logger()->error($this->getMessage(), [
                'exception' => get_class($this),
                'file' => $this->getFile(),
                'line' => $this->getLine(),
                'errors' => $this->getErrors(),
                'trace' => $this->getTraceAsString(),
            ]);
        }
    }
}
