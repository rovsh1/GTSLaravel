<?php

namespace Sdk\Shared\Enum;

enum CurrencyEnum: string
{
    private const ID_MAP = [
        'UZS' => 1,
        'RUB' => 2,
        'USD' => 3,
        'KZT' => 4,
        'EUR' => 10,
    ];

    case UZS = 'UZS';
    case RUB = 'RUB';
    case USD = 'USD';
    case KZT = 'KZT';
    case EUR = 'EUR';

    public function id(): int
    {
        return self::ID_MAP[$this->value];
    }

    /**
     * @param int $id
     * @return CurrencyEnum|null
     * @deprecated
     */
    public static function fromId(int $id): ?CurrencyEnum
    {
        return false === ($key = array_search($id, self::ID_MAP))
            ? null
            : self::from($key);
    }
}
