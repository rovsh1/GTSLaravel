<?php

namespace Module\Booking\PriceCalculator\Domain\Service\HotelBooking\Formula;

final class HORoomPriceFormula extends AbstractRoomPriceFormula
{
    public function calculate(int|float $netValue): float
    {
        /**
         * S (итоговая сумма по номеру) = Sh * n * k + Z * n
         *
         * Sh – цена нетто номера за 1 ночь
         * n – количество номеров (=1)
         * k – количество ночей
         * Z – надбавка за ранний заезд/поздний выезд = Sh * (процент/100)
         */

        $Sh = $this->variables->isResident
            ? $this->calculateResidentNetPrice($netValue)
            : $this->calculateNonResidentNetPrice($netValue, $this->variables->guestsCount);
        $n = 1;
        $k = $this->variables->nightsCount;
        $Z = $this->calculateConditionMarkup($Sh);

        return (float)($Sh * $k + $Z);
    }

    private function calculateResidentNetPrice(int|float $netValue): float
    {
        /**
         * Формула расчета стоимости номера для резидента за 1 ночь:
         * Sh = Sn + NDS
         *
         * Sn – нетто стоимость номера отеля
         * NDS (НДС общий для резидентов и нерезидентов) = Sn * (процент НДС/100)
         */
        $Sn = $netValue;
        $NDS = $this->calculateNds($Sn);

        return (float)($Sn + $NDS);
    }

    private function calculateNonResidentNetPrice(int|float $netValue, int $guestsCount): float
    {
        /**
         * Формула расчета стоимости номера для нерезидента за 1 ночь:
         * Sh = Sn + NDS + T * n
         *
         * Sn – нетто стоимость номера отеля
         * NDS (НДС общий для резидентов и нерезидентов) = Sn * (процент НДС/100)
         * T – турсбор = BRV * (процент турсбора/100)
         * BRV – Базовая расчетная величина
         * n – количество гостей, проживающих в номере (=кол-во человек в типе размещения)
         */
        $Sn = $netValue;
        $NDS = $this->calculateNds($Sn);
        $T = $this->markupVariables->touristTax;
        $n = $guestsCount;

        return (float)($Sn + $NDS + $T * $n);
    }
}
