<?php

namespace Module\Booking\HotelBooking\Domain\Service\PriceCalculator\Formula;

use Module\Booking\HotelBooking\Domain\Service\PriceCalculator\Support\FormulaUtil;

final class EstimatedPriceCalculator
{
    /**
     * Расчетная стоимость за ночь:
     *
     * Sb = Sn + N + NDS + T * n
     *
     * Sn – базовая стоимость номера отеля (из таблицы)
     * N – наценка
     * NDS = (Sn + N) * (процент НДС/100)
     * T – турсбор
     * n – количество гостей, проживающих в номере (=кол-во человек в типе размещения)
     */

    public function calculate(
        int|float $basePrice,
        int $markupPercent,
        int $ndsPercent,
        int $touristTax,
        int $guestsCount
    ): float {
        $markupValue = FormulaUtil::calculatePercent($basePrice, $markupPercent);
        $ndsValue = FormulaUtil::calculatePercent($basePrice + $markupValue, $ndsPercent);

        return $basePrice + $markupValue + $ndsValue + ($touristTax * $guestsCount);
    }
}
