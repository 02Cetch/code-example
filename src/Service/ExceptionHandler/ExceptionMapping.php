<?php

namespace App\Service\ExceptionHandler;

class ExceptionMapping
{
    public function __construct(
        private readonly int $code,
        private readonly bool $hidden,
        private readonly bool $loggable
    ) {
    }

    public static function fromCode(int $code): self
    {
        return new self($code, true, false);
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @return bool
     */
    public function isHidden(): bool
    {
        return $this->hidden;
    }

    /**
     * @return bool
     */
    public function isLoggable(): bool
    {
        return $this->loggable;
    }
}
