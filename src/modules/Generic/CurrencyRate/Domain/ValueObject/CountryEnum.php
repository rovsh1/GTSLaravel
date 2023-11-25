<?php

namespace Module\Generic\CurrencyRate\Domain\ValueObject;

use Sdk\Shared\Enum\CurrencyEnum;

enum CountryEnum: string
{
    case RU = 'RU';
    case UZ = 'UZ';
    //case KZ = 'KZ';

    private const DEFAULT_CURRENCIES = [
        'RU' => CurrencyEnum::RUB,
        'UZ' => CurrencyEnum::UZS,
        //'KZ' => CurrencyEnum::KZT,
    ];

    public function currency(): CurrencyEnum
    {
        return self::DEFAULT_CURRENCIES[$this->value];
    }
}