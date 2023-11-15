<?php

declare(strict_types=1);

namespace Module\Supplier\Moderation\Domain\Supplier\ValueObject;

use Module\Shared\Contracts\Domain\ValueObjectInterface;

class Requisites implements ValueObjectInterface
{
    public function __construct(
        private readonly string $inn,
        private readonly string $directorFullName,
    ) {}

    public function inn(): string
    {
        return $this->inn;
    }

    public function directorFullName(): string
    {
        return $this->directorFullName;
    }
}
