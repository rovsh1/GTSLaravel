<?php

namespace Module\Booking\HotelBooking\Domain\Service\PriceCalculator\Formula;

use Module\Booking\HotelBooking\Domain\Service\PriceCalculator\Model\CalculationResult;

final class DayPriceFormula
{
    /**
     * Итоговая стоимость по номеру за ночь:
     *
     * S = (Sb - D) + Z
     *
     * Sb – Расчетная стоимость за ночь
     * D – сумма скидки = Sn * (процент/100), где Sn – цена нетто номера за 1 ночь
     * Z – надбавка за ранний заезд/поздний выезд = Sb * (процент/100)
     */

    public function evaluate(
        int|float $estimatedValue,
        float $markupValue,
        int $discountValue = 0
    ): CalculationResult {
        $notes = self::noteValue($estimatedValue, 'расчетная');

        if ($markupValue) {
            $notes .= ' ' . self::noteValue($markupValue, 'надбавка');
        }

        return new CalculationResult(
            $estimatedValue - $discountValue + $markupValue,
            $notes
        );
    }

    private static function noteValue(int|float $value, string $hint): string
    {
        return self::numberFormat($value) . " ($hint)";
    }

    private static function numberFormat(int|float $value): string
    {
        return number_format($value, 0, '.', ' ');
    }
}
