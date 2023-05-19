<?php

declare(strict_types=1);

namespace Module\Shared\Domain\ValueObject;

class Uint implements ValueObjectInterface
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
}
