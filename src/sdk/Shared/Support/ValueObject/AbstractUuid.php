<?php

declare(strict_types=1);

namespace Sdk\Shared\Support\ValueObject;

use Illuminate\Support\Str;
use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Sdk\Shared\Contracts\Support\CanEquate;

abstract class AbstractUuid implements CanEquate, ValueObjectInterface
{
    private string $id;

    public static function createNew(): static
    {
        return new static(Str::uuid()->toString());
    }

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function value(): string
    {
        return $this->id;
    }

    /**
     * @param AbstractUuid|string $b
     * @return bool
     */
    public function isEqual(mixed $b): bool
    {
        return $b instanceof AbstractUuid
            ? $this->id === $b->value()
            : $this->id === $b;
    }

    public function __toString(): string
    {
        return $this->id;
    }
}
