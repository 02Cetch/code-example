<?php

namespace App\Dto\Response;

class ErrorResponse
{
    public function __construct(private readonly string $message, private readonly int $code)
    {
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getData(): array
    {
        return [];
    }
}
