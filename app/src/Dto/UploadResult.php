<?php

namespace App\Dto;

class UploadResult
{
    private bool $success;
    private string $message;
    private ?string $fileName;

    private ?string $uploadDirectory;

    private ?int $databaseId;

    public function __construct(bool $success, string $message, ?string $fileName = null, ?string $uploadDirectory = null)
    {
        $this->success = $success;
        $this->message = $message;
        $this->fileName = $fileName;
        $this->uploadDirectory = $uploadDirectory;
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function setSuccess(bool $success): void
    {
        $this->success = $success;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function getUploadDirectory(): ?string
    {
        return $this->uploadDirectory;
    }

    public function getDatabaseId(): ?int
    {
        return $this->databaseId;
    }

    public function setDatabaseId(?int $databaseId): void
    {
        $this->databaseId = $databaseId;
    }
}
