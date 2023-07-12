<?php

namespace Module\Booking\PriceCalculator\Domain\Service\Hotel\Formula;

use Module\Booking\PriceCalculator\Domain\Service\Hotel\Support\FormulaUtil;

final class HONonResidentDayPriceFormula implements DayPriceFormulaInterface
{
    /**
     * Формула расчета стоимости номера для нерезидента за 1 ночь:
     * Sb = Sn + NDS + T * n
     *
     * Sn – нетто стоимость номера отеля
     * NDS = (Sn + N) * (процент НДС/100)
     * T – турсбор
     * n – количество гостей, проживающих в номере (=кол-во человек в типе размещения)
     */
    public function __construct(
        private readonly int $ndsPercent,
        private readonly int $touristTax,
        private readonly int $guestsCount
    ) {
    }

    public function calculate(int|float $netValue): float
    {
        return $netValue
            + FormulaUtil::calculatePercent($netValue, $this->ndsPercent)
            + $this->touristTax * $this->guestsCount;
    }
}
