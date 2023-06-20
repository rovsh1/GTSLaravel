<?php

namespace Module\Booking\PriceCalculator\Domain\Service\HotelBooking\Formula;

final class BORoomPriceFormula extends AbstractRoomPriceFormula
{
    public function calculate(int|float $netValue): float
    {
        /**
         * S (итоговая сумма по номеру) = (Sb – D) * n * k + Z * n
         *
         * Sb – цена брутто номера за 1 ночь
         * n – количество номеров (=1)
         * k – количество ночей
         * D – сумма скидки = Sn * (процент/100), где Sn – цена нетто номера за 1 ночь
         * Z – надбавка за ранний заезд/поздний выезд = Sb * (процент/100)
         */

        $Sb = $this->variables->isResident
            ? $this->calculateResidentGrossPrice($netValue)
            : $this->calculateNonResidentGrossPrice($netValue, $this->variables->guestsCount);
        $D = 0;
        $k = $this->variables->nightsCount;
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
        $T = $this->markupVariables->touristTax;
        $n = $guestsCount;

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
