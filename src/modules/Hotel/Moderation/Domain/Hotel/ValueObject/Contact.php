<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Domain\Hotel\ValueObject;

use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Enum\ContactTypeEnum;

class Contact implements ValueObjectInterface
{
    public function __construct(
        private readonly ContactTypeEnum $type,
        private readonly string $value,
        private readonly bool $isMain
    ) {}

    public function type(): ContactTypeEnum
    {
        return $this->type;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function isMain(): bool
    {
        return $this->isMain;
    }
}
