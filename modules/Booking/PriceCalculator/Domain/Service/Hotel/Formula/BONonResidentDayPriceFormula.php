<?php

namespace Module\Booking\PriceCalculator\Domain\Service\Hotel\Formula;

use Module\Booking\PriceCalculator\Domain\Service\Hotel\Support\FormulaUtil;

final class BONonResidentDayPriceFormula implements DayPriceFormulaInterface
{
    /**
     * Формула расчета стоимости номера для нерезидента за 1 ночь:
     * Sb = Sn + N + NDS + T * n
     *
     * Sn – нетто стоимость номера отеля
     * N – наценка
     * NDS = (Sn + N) * (процент НДС/100)
     * T – турсбор
     * n – количество гостей, проживающих в номере (=кол-во человек в типе размещения)
     */
    public function __construct(
        private readonly int|float $clientMarkupPercent,
        private readonly int $ndsPercent,
        private readonly int $touristTax,
        private readonly int $guestsCount
    ) {
    }

    public function calculate(int|float $netValue): float
    {
        return $netValue
            + ($N = FormulaUtil::calculatePercent($netValue, $this->clientMarkupPercent))
            + FormulaUtil::calculatePercent($netValue + $N, $this->ndsPercent)
            + $this->touristTax * $this->guestsCount;
    }
}
