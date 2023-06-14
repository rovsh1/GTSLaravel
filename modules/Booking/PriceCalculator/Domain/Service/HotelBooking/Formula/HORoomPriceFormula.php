<?php

namespace Module\Booking\PriceCalculator\Domain\Service\HotelBooking\Formula;

class HORoomPriceFormula
{
    public function __construct(
        private readonly FormulaVariables $variables
    ) {
    }

    public function calculate(
        int|float $netValue,
        bool $isResident,
        int $guestsCount,
        int $nightsCount
    ): float {
        /**
         * S (итоговая сумма по номеру) = (Sb – D) * n * k + Z * n
         *
         * Sb – цена брутто номера за 1 ночь
         * n – количество номеров
         * k – количество ночей
         * D – сумма скидки = Sn * (процент/100), где Sn – цена нетто номера за 1 ночь
         * Z – надбавка за ранний заезд/поздний выезд = Sb * (процент/100)
         */

        $Sb = $isResident
            ? $this->calculateResidentGrossPrice($netValue)
            : $this->calculateNonResidentGrossPrice($netValue, $guestsCount);
        $D = 0;
        $k = $nightsCount;
        $Z = $this->calculateConditionMarkup($Sb);

        return (float)(($Sb - $D) * $k + $Z);
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
        $T = $this->variables->touristTax;
        $n = $guestsCount;

        return (float)($Sn + $N + $NDS + $T * $n);
    }

    /**
     * Если наценка указана в процентах, тогда N = Sn * (процент наценки/100)
     */
    private function calculateClientMarkup(int|float $value): float
    {
        return $value * $this->variables->clientMarkupPercent / 100;
    }

    private function calculateConditionMarkup(float $value): float
    {
        return $value * $this->variables->conditionalMarkupPercent / 100;
    }

    /**
     * НДС общий для резидентов и нерезидентов
     */
    private function calculateNds(int|float $value): float
    {
        return $value * $this->variables->vatPercent / 100;
    }
}
