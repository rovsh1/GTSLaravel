<?php

namespace Module\Booking\PriceCalculator\Domain\Service\Hotel\Formula;

use Module\Booking\PriceCalculator\Domain\Service\ExpressionEvaluator;
use Module\Booking\PriceCalculator\Domain\Service\Hotel\Support\FormulaUtil;
use Module\Booking\PriceCalculator\Domain\ValueObject\CalculationResult;

final class HORoomPriceFormula implements RoomPriceFormulaInterface
{
    /**
     * S (итоговая сумма по номеру) = Sh * n * k + Z * n
     *
     * Sh – цена нетто номера за 1 ночь
     * n – количество номеров (=1)
     * k – количество ночей
     * Z – надбавка за ранний заезд/поздний выезд = Sh * (процент/100)
     */
    private string $expression = '(Sb * k + Z) * n';

    public function __construct(
        private readonly int|float|null $earlyCheckInPercent,
        private readonly int|float|null $lateCheckInPercent,
        //private readonly int $nightsCount,
    ) {}

    public function evaluate(int|float $dayPrice): CalculationResult
    {
        return (new ExpressionEvaluator($this->expression))
            ->addVariable('Sb', $dayPrice, 'брутто')
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
