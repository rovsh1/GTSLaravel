<?php

declare(strict_types=1);

namespace Module\Shared\Support\Dto;

use Module\Shared\Contracts\Service\TranslatorInterface;

abstract class AbstractEnumDto extends \Sdk\Module\Foundation\Support\Dto\Dto
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