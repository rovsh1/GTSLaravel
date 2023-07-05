<?php

namespace Module\Booking\PriceCalculator\Domain\Service\HotelBooking\Formula;

use Module\Booking\PriceCalculator\Domain\Service\ExpressionEvaluator;
use Module\Booking\PriceCalculator\Domain\ValueObject\CalculationResult;

final class BORoomPriceFormula extends AbstractRoomPriceFormula
{
    public function calculate(int|float $dayPrice): CalculationResult
    {
        /**
         * S (итоговая сумма по номеру) = (Sb - D) * n * k + Z * n
         *
         * Sb – цена брутто номера за 1 ночь
         * n – количество номеров (=1)
         * k – количество ночей
         * D – сумма скидки = Sn * (процент/100), где Sn – цена нетто номера за 1 ночь
         * Z – надбавка за ранний заезд/поздний выезд = Sb * (процент/100)
         */
        $Sb = $dayPrice;

        return (new ExpressionEvaluator('((Sb - D) * k + Z) * n'))
            ->addVariable('Sb', $Sb, 'брутто')
            ->addVariable('D', 0, 'скидка')
            ->addVariable('n', 1, 'номеров')
            ->addVariable('k', $this->variables->nightsCount, 'ночей')
            ->addVariable('Z', $this->calculateConditionMarkup($Sb), 'надбавка')
            ->evaluate();
    }

    public function calculateDayPrice(int|float $netValue): float
    {
        return $this->variables->isResident
            ? $this->calculateResidentGrossPrice($netValue)
            : $this->calculateNonResidentGrossPrice($netValue, $this->variables->guestsCount);
    }

    private function calculateResidentGrossPrice(int|float $netValue): float
    {
        /**
         * 2. Формула расчета стоимости номера для резидента за 1 ночь:
         * Sb = Sn + N + NDS
         *
         * Sn – нетто стоимость номера отеля
         * N – наценка
         * NDS = (Sn + N) * (процент НДС/100)
         */
        $Sn = $netValue;
        $N = $this->calculateClientMarkup($Sn);
        $NDS = $this->calculateNds($Sn + $N);

        //dump(compact('Sn', 'N', 'NDS'));

        return (float)($Sn + $N + $NDS);
    }

    private function calculateNonResidentGrossPrice(int|float $netValue, int $guestsCount): float
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
        $Sn = $netValue;
        $N = $this->calculateClientMarkup($Sn);
        $NDS = $this->calculateNds($Sn + $N);
        $T = $this->markupVariables->touristTax;
        $n = $guestsCount;

        //dump(compact('Sn', 'N', 'NDS', 'T'));

        return (float)($Sn + $N + $NDS + $T * $n);
    }

    /**
     * Если наценка указана в процентах, тогда N = Sn * (процент наценки/100)
     */
    private function calculateClientMarkup(int|float $value): float
    {
        return $value * $this->markupVariables->clientMarkupPercent / 100;
    }
}
