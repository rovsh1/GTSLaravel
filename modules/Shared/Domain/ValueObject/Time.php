<?php

declare(strict_types=1);

namespace Module\Shared\Domain\ValueObject;

class Time
{
    private string $value;

    public function __construct(string $value)
    {
        $this->setValue($value);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function setValue(string $value): void
    {
        $this->validateTime($value);
        $this->value = $value;
    }

    /**
     * @param string $value
     * @return void
     * @throws \InvalidArgumentException
     */
    private function validateTime(string $value): void
    {
        if (!preg_match('/^([0-1]?[0-9]|2[0-4]):[0-5][0-9]$/m', $value)) {
            throw new \InvalidArgumentException("Invalid time value [{$value}]");
        }
    }
}
