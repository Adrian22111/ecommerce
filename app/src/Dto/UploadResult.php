<?php

namespace App\Dto;

class UploadResult
{
    private bool $success;
    private string $message;
    private ?string $fileName;

    public function __construct(bool $success, string $message, ?string $fileName = null)
    {
        $this->success = $success;
        $this->message = $message;
        $this->fileName = $fileName;
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }
}