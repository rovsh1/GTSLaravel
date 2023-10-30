<?php

declare(strict_types=1);

namespace Module\Shared\Dto;

use Module\Shared\Contracts\Service\TranslatorInterface;
use Module\Shared\Enum\CurrencyEnum;
use Module\Shared\Support\Dto\AbstractEnumDto;

class CurrencyDto extends AbstractEnumDto
{
    public function __construct(public readonly int $id, int|string $value, string $name)
    {
        parent::__construct($value, $name);
    }

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
