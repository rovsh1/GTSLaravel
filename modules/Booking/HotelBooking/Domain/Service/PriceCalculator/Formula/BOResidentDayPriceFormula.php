<?php

namespace Module\Booking\HotelBooking\Domain\Service\PriceCalculator\Formula;

use Module\Booking\HotelBooking\Domain\Service\PriceCalculator\Support\FormulaUtil;

final class BOResidentDayPriceFormula implements DayPriceFormulaInterface
{
    /**
     * 2. Формула расчета стоимости номера для резидента за 1 ночь:
     * Sb = Sn + N + NDS
     *
     * Sn – нетто стоимость номера отеля
     * N – наценка
     * NDS = (Sn + N) * (процент НДС/100)
     */
    public function __construct(
        private readonly int|float $clientMarkupPercent,
        private readonly int $ndsPercent
    ) {
    }

    public function calculate(int|float $netValue): float
    {
        return $netValue
            + ($N = FormulaUtil::calculatePercent($netValue, $this->clientMarkupPercent))
            + FormulaUtil::calculatePercent($netValue + $N, $this->ndsPercent);
    }
}
