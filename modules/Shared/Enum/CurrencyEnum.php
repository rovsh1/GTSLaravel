<?php

namespace Module\Shared\Enum;

enum CurrencyEnum: int
{
//    case BYN = 'BYN';
    case EUR = 10;
    case RUB = 2;
    case USD = 3;
    case KZT = 4;
//    case CHF = 'CHF';
//    case SEK = 'SEK';
//    case UAH = 'UAH';
//    case GBP = 'GBP';
//    case CZK = 'CZK';
//    case NOK = 'NOK';
//    case DKK = 'DKK';
//    case JPY = 'JPY';
    case UZS = 1;

    public static function tryFromName(string $name): ?static
    {
        foreach (self::cases() as $case) {
            if ($case->name === $name) {
                return $case;
            }
        }

        return null;
    }
}
