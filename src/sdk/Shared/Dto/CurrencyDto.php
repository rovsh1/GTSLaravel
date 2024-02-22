<?php

declare(strict_types=1);

namespace Sdk\Shared\Dto;

use Sdk\Shared\Contracts\Service\TranslatorInterface;
use Sdk\Shared\Enum\CurrencyEnum;

class CurrencyDto
{
    public function __construct(
        public readonly int $id,
        public readonly int|string $value,
        public readonly string $name,
    ) {}

    public static function fromEnum(\BackedEnum $enum, TranslatorInterface $translator): static
    {
        assert($enum instanceof CurrencyEnum);

        return new static(
            $enum->id(),
            $enum->value,
            $translator->translateEnum($enum)
        );
    }
}
