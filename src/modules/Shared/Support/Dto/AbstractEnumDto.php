<?php

declare(strict_types=1);

namespace Module\Shared\Support\Dto;

use Sdk\Shared\Contracts\Service\TranslatorInterface;

abstract class AbstractEnumDto
{
    public function __construct(
        public readonly int|string $value,
        public readonly string $name,
    ) {}

    public static function fromEnum(\BackedEnum $enum, TranslatorInterface $translator): static
    {
        return new static(
            $enum->value,
            $translator->translateEnum($enum)
        );
    }
}
