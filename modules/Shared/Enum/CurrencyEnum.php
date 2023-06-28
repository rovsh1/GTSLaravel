<?php

namespace Module\Shared\Enum;

enum CurrencyEnum: string
{
    private const UZS_ID = 1;
    private const RUB_ID = 2;
    private const USD_ID = 3;
    private const KZT_ID = 4;
    private const EUR_ID = 10;

//    case BYN = 'BYN';
    case EUR = 'EUR';
    case RUB = 'RUB';
    case USD = 'USD';
    case KZT = 'KZT';
//    case CHF = 'CHF';
//    case SEK = 'SEK';
//    case UAH = 'UAH';
//    case GBP = 'GBP';
//    case CZK = 'CZK';
//    case NOK = 'NOK';
//    case DKK = 'DKK';
//    case JPY = 'JPY';
    case UZS = 'UZS';

    public function id(): int
    {
        return match ($this) {
            self::UZS => self::UZS_ID,
            self::RUB => self::RUB_ID,
            self::USD => self::USD_ID,
            self::KZT => self::KZT_ID,
            self::EUR => self::EUR_ID,
        };
    }

    public static function fromId(int $id): ?static
    {
        return match ($id) {
            self::UZS_ID => self::UZS,
            self::RUB_ID => self::RUB,
            self::USD_ID => self::USD,
            self::KZT_ID => self::KZT,
            self::EUR_ID => self::EUR,
        };
    }
}
