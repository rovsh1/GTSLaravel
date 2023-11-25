<?php

declare(strict_types=1);

namespace Sdk\Shared\Dto;

use Module\Shared\Support\Dto\AbstractEnumDto;
use Sdk\Shared\Contracts\Service\TranslatorInterface;
use Sdk\Shared\Enum\CurrencyEnum;

class CurrencyDto extends AbstractEnumDto
{
    public function __construct(public readonly int $id, int|string $value, string $name)
    {
        parent::__construct($value, $name);
    }

    //@todo remove from here
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
