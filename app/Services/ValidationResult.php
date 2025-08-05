<?php

namespace App\Services;

class ValidationResult
{
    public bool $isValid;
    public array $errors;
    public array $warnings;
    public ?array $metadata;

    public function __construct(bool $isValid = true, array $errors = [], array $warnings = [], ?array $metadata = null)
    {
        $this->isValid = $isValid;
        $this->errors = $errors;
        $this->warnings = $warnings;
        $this->metadata = $metadata;
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    public function hasWarnings(): bool
    {
        return !empty($this->warnings);
    }

    public function addError(string $error): void
    {
        $this->errors[] = $error;
        $this->isValid = false;
    }

    public function addWarning(string $warning): void
    {
        $this->warnings[] = $warning;
    }

    public function getAllMessages(): array
    {
        return array_merge($this->errors, $this->warnings);
    }

    public function toArray(): array
    {
        return [
            'is_valid' => $this->isValid,
            'errors' => $this->errors,
            'warnings' => $this->warnings,
            'metadata' => $this->metadata,
        ];
    }
}