<?php

declare(strict_types=1);

namespace Sdk\Shared\ValueObject;

use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Contracts\Support\CanEquate;

class Uint implements ValueObjectInterface, CanEquate
{
    protected readonly int $value;

    public function __construct(int $value)
    {
        $this->value = $this->validatePositiveInteger($value);
    }

    public function value(): int
    {
        return $this->value;
    }

    /**
     * @param int $value
     * @return int
     * @throws \InvalidArgumentException
     */
    private function validatePositiveInteger(int $value): int
    {
        if ($value < 0) {
            throw new \InvalidArgumentException("Invalid Uint value [{$value}]");
        }

        return $value;
    }

    public function isEqual(mixed $b): bool
    {
        return $b instanceof Uint
            ? $this->value === $b->value
            : $this === $b;
    }
}
