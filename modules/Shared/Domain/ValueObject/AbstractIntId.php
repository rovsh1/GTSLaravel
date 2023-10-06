<?php

declare(strict_types=1);

namespace Module\Shared\Domain\ValueObject;

use Module\Shared\Contracts\CanEquate;

abstract class AbstractIntId implements CanEquate, ValueObjectInterface
{
    private int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function value(): int
    {
        return $this->id;
    }

    /**
     * @param AbstractIntId|int $b
     * @return bool
     */
    public function isEqual(mixed $b): bool
    {
        return $b instanceof AbstractIntId
            ? $this->id === $b->value()
            : $this->id === $b;
    }

    public function __toString(): string
    {
        return (string)$this->id;
    }
}
