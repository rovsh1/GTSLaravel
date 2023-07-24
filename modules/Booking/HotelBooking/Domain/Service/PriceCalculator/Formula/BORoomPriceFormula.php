<?php

namespace Module\Booking\HotelBooking\Domain\Service\PriceCalculator\Formula;

use Module\Booking\Common\Domain\Support\CalculationResult;
use Module\Booking\Common\Domain\Support\ExpressionEvaluator;
use Module\Booking\HotelBooking\Domain\Service\PriceCalculator\Support\FormulaUtil;

final class BORoomPriceFormula implements RoomPriceFormulaInterface
{
    /**
     * S (итоговая сумма по номеру) = (Sb - D) * n * k + Z * n
     *
     * Sb – цена брутто номера за 1 ночь
     * n – количество номеров (=1)
     * k – количество ночей (=1)
     * D – сумма скидки = Sn * (процент/100), где Sn – цена нетто номера за 1 ночь
     * Z – надбавка за ранний заезд/поздний выезд = Sb * (процент/100)
     */
    private string $expression = '((Sb - D) * k + Z) * n';

    public function __construct(
        private readonly int|float|null $earlyCheckInPercent,
        private readonly int|float|null $lateCheckInPercent,
        //private readonly int $nightsCount,
    )
    {
    }

    public function evaluate(int|float $dayPrice): CalculationResult
    {
        return (new ExpressionEvaluator($this->expression))
            ->addVariable('Sb', $dayPrice, 'брутто')
            ->addVariable('D', 0, 'скидка')
            ->addVariable('n', 1, 'номеров')
            ->addVariable('k', 1, 'ночей')
            ->addVariable(
                'Z',
                FormulaUtil::calculateConditionMarkup($dayPrice, $this->earlyCheckInPercent, $this->lateCheckInPercent),
                'надбавка'
            )
            ->evaluate();
    }
}
