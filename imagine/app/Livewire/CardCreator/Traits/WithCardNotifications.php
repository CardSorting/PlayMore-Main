<?php

namespace App\Livewire\CardCreator\Traits;

trait WithCardNotifications
{
    protected function notifySuccess(string $message, ?string $action = null): void
    {
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => $message,
            'action' => $action,
            'duration' => 3000
        ]);
    }

    protected function notifyError(string $message, ?string $action = null): void
    {
        $this->dispatch('notify', [
            'type' => 'error',
            'message' => $message,
            'action' => $action,
            'duration' => 5000
        ]);
    }

    protected function notifyWarning(string $message, ?string $action = null): void
    {
        $this->dispatch('notify', [
            'type' => 'warning',
            'message' => $message,
            'action' => $action,
            'duration' => 4000
        ]);
    }

    protected function notifyInfo(string $message, ?string $action = null): void
    {
        $this->dispatch('notify', [
            'type' => 'info',
            'message' => $message,
            'action' => $action,
            'duration' => 3000
        ]);
    }

    protected function notifyValidationError(string $field): void
    {
        $error = $this->getErrorMessage($field);
        if ($error) {
            $this->notifyError($error, 'Fix Error');
        }
    }

    protected function notifyStateChange(string $message): void
    {
        $this->dispatch('notify', [
            'type' => 'info',
            'message' => $message,
            'duration' => 2000,
            'position' => 'bottom'
        ]);
    }

    protected function notifyUnsavedChanges(): void
    {
        if ($this->isDirty()) {
            $this->dispatch('notify', [
                'type' => 'warning',
                'message' => 'You have unsaved changes',
                'action' => 'Save',
                'persistent' => true
            ]);
        }
    }

    protected function notifyInvalidOperation(string $message): void
    {
        $this->dispatch('notify', [
            'type' => 'error',
            'message' => $message,
            'icon' => 'ban',
            'duration' => 4000
        ]);
    }

    protected function notifyRequiredField(string $field): void
    {
        $fieldName = ucwords(str_replace('_', ' ', $field));
        $this->notifyError("{$fieldName} is required");
    }

    protected function notifyMaxLength(string $field, int $maxLength): void
    {
        $fieldName = ucwords(str_replace('_', ' ', $field));
        $this->notifyError("{$fieldName} cannot exceed {$maxLength} characters");
    }

    protected function notifyInvalidFormat(string $field, string $format): void
    {
        $fieldName = ucwords(str_replace('_', ' ', $field));
        $this->notifyError("{$fieldName} must be in format: {$format}");
    }

    protected function notifySuccessfulSave(): void
    {
        $this->notifySuccess('Card saved successfully', 'View Card');
    }

    protected function notifyFailedSave(string $error): void
    {
        $this->notifyError("Failed to save card: {$error}", 'Try Again');
    }

    protected function notifyStateRestored(): void
    {
        $this->notifyInfo('Previous progress restored', 'Start Fresh');
    }

    protected function notifyStateCleared(): void
    {
        $this->notifyInfo('Progress cleared');
    }

    protected function getNotificationConfig(): array
    {
        return [
            'position' => 'top-right',
            'duration' => 3000,
            'closeable' => true,
            'styles' => [
                'success' => 'bg-green-500',
                'error' => 'bg-red-500',
                'warning' => 'bg-yellow-500',
                'info' => 'bg-blue-500'
            ]
        ];
    }
}
