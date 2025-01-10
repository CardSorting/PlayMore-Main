<?php

namespace App\Exceptions;

use Exception;

class PaymentException extends Exception
{
    protected array $errors = [];

    public function __construct(string $message = "", int $code = 0, ?\Throwable $previous = null, array $errors = [])
    {
        parent::__construct($message, $code, $previous);
        $this->errors = $errors;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function render($request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $this->getMessage(),
                'errors' => $this->getErrors(),
            ], 422);
        }

        return back()->withErrors([
            'payment' => $this->getMessage(),
        ])->withInput();
    }
}
