<?php

namespace Module\Booking\PriceCalculator\Domain\Service\Hotel\Formula;

use Module\Booking\PriceCalculator\Domain\Service\Hotel\Support\FormulaUtil;

final class HOResidentDayPriceFormula implements DayPriceFormulaInterface
{
    /**
     * 2. Формула расчета стоимости номера для резидента за 1 ночь:
     * Sb = Sn + NDS
     *
     * Sn – нетто стоимость номера отеля
     * NDS = (Sn + N) * (процент НДС/100)
     */
    public function __construct(
        private readonly int $ndsPercent
    ) {
    }

    public function calculate(int|float $netValue): float
    {
        return $netValue
            + FormulaUtil::calculatePercent($netValue, $this->ndsPercent);
    }
}