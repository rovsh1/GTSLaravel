<?php

declare(strict_types=1);

namespace Sdk\Shared\ValueObject;

use Sdk\Shared\Contracts\Support\CanEquate;
use Sdk\Shared\Enum\CurrencyEnum;

final class Money implements CanEquate
{
    private readonly float $value;

    public function __construct(
        private readonly CurrencyEnum $currency,
        float $value,
    ) {
        $this->validateValue($value);
        $this->value = $value;
    }

    public static function round(CurrencyEnum $currency, float|int $value): float
    {
        return round($value, self::getDecimalsCount($currency));
    }

    public static function roundNullable(CurrencyEnum $currency, float|int|null $value): ?float
    {
        return $value === null ? null : static::round($currency, $value);
    }

    public static function getDecimalsCount(CurrencyEnum $currency): int
    {
        return match ($currency) {
            CurrencyEnum::UZS,
            CurrencyEnum::KZT,
            CurrencyEnum::RUB => 0,
            CurrencyEnum::USD,
            CurrencyEnum::EUR => 2,
        };
    }

    public static function countDecimals(float $number): int
    {
        if ((int)$number == $number) {
            // Если число целое, то знаков после запятой нет
            return 0;
        }

        $numberAsString = (string)$number;
        $decimalPosition = strpos($numberAsString, '.');

        if ($decimalPosition === false) {
            return 0;
        }

        return strlen($numberAsString) - $decimalPosition - 1;
    }

    public function currency(): CurrencyEnum
    {
        return $this->currency;
    }

    public function value(): float
    {
        return $this->value;
    }

    /**
     * @param Money $b
     * @return bool
     */
    public function isEqual(mixed $b): bool
    {
        if (!$b instanceof Money) {
            return false;
        }

        return $this->value === $b->value
            && $this->currency === $b->currency;
    }

    public function isZero(): bool
    {
        return $this->value === 0.0;
    }

    private function validateValue(float $value): void
    {
        if ($value < 0.0) {
            throw new \InvalidArgumentException('Money can\'t be negative');
        }
        $decimalsCount = self::countDecimals($value);
        if ($decimalsCount > self::getDecimalsCount($this->currency)) {
            throw new \InvalidArgumentException('Invalid decimals');
        }
    }
}
