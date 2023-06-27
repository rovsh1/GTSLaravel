<?php

namespace Module\Booking\PriceCalculator\Domain\Service\HotelBooking;

use Carbon\CarbonInterface;
use Module\Booking\Common\Domain\ValueObject\PriceCalculationNotes;
use Module\Booking\Hotel\Domain\Entity\Booking;
use Module\Booking\Hotel\Domain\ValueObject\RoomPrice;
use Module\Booking\PriceCalculator\Domain\Adapter\HotelAdapterInterface;
use Module\Booking\PriceCalculator\Domain\Service\HotelBooking\Formula\BORoomPriceFormula;
use Module\Booking\PriceCalculator\Domain\Service\HotelBooking\Formula\HORoomPriceFormula;
use Module\Booking\PriceCalculator\Domain\Service\HotelBooking\Formula\MarkupVariables;
use Module\Booking\PriceCalculator\Domain\Service\HotelBooking\Formula\RoomVariables;

class RoomCalculator
{
    public function __construct(
        private readonly HotelAdapterInterface $hotelAdapter,
        private readonly VariablesBuilder $variablesBuilder
    ) {
    }

    public function calculateByBooking(
        Booking $hotelBooking,
        int $roomId,
        int $rateId,
        bool $isResident,
        int $guestsCount,
        ?int $earlyCheckInPercent,
        ?int $lateCheckOutPercent
    ): RoomPrice {
        return $this->calculate(
            $this->variablesBuilder->build(
                $hotelBooking,
                $roomId,
                $rateId,
                $isResident,
                $guestsCount,
                $earlyCheckInPercent,
                $lateCheckOutPercent
            )
        );
    }

    public function calculate(CalculateVariables $calculateVariables): RoomPrice
    {
        $markupVariables = $this->buildMarkupVariables($calculateVariables);
        $formulaVariables = new RoomVariables($calculateVariables->isResident, $calculateVariables->guestsCount, 1);
        $hoFormula = new HORoomPriceFormula($markupVariables, $formulaVariables);
        $boFormula = new BORoomPriceFormula($markupVariables, $formulaVariables);

        $dates = $calculateVariables->bookingPeriod->includedDates();

        $netValue = 0.0;
        $hoValue = 0.0;
        $boValue = 0.0;
        $hoNotes = null;
        $boNotes = null;
        foreach ($dates as $date) {
            $datePrice = $this->getDatePrice(
                $calculateVariables->roomId,
                $calculateVariables->rateId,
                $calculateVariables->isResident,
                $calculateVariables->guestsCount,
                $date
            );
            $netValue += $datePrice;
            $hoResult = $hoFormula->calculate($datePrice);
            $boResult = $boFormula->calculate($datePrice);
            $hoValue += $hoResult->value;
            $boValue += $boResult->value;
            if (null === $hoNotes) {
                $hoNotes = $hoResult->notes;
                $boNotes = $boResult->notes;
            }
        }
        $avgDailyValue = $netValue / $calculateVariables->bookingPeriod->nightsCount();

        return new RoomPrice(
            $netValue,
            $avgDailyValue,
            $hoValue,
            $boValue,
            new PriceCalculationNotes(
                $hoNotes,
                $boNotes
            )
        );
    }

    private function buildMarkupVariables(CalculateVariables $calculateVariables): MarkupVariables
    {
        return new MarkupVariables(
            $calculateVariables->vatPercent,
            $calculateVariables->clientMarkupPercent,
            $calculateVariables->earlyCheckInPercent,
            $calculateVariables->lateCheckOutPercent,
            $calculateVariables->touristTax
        );
//        $markupDto = $this->hotelAdapter->getMarkupSettings($hotelBooking->hotelInfo()->id());
//        $order = $this->orderRepository->find($hotelBooking->orderId()->value());
//        $clientDto = $this->clientAdapter->find($order->clientId());
//        $clientType = 'TO';
//
//        return new MarkupVariables(
//            $markupDto->vat,
//            $markupDto->clientMarkups->$clientType,
//            $calculateVariables->earlyCheckInPercent,
//            $calculateVariables->lateCheckOutPercent,
//            $markupDto->touristTax
//        );
    }

    private function getDatePrice(
        int $roomId,
        int $rateId,
        bool $isResident,
        int $guestsCount,
        CarbonInterface $date
    ): float {
        return $this->hotelAdapter->getRoomPrice($roomId, $rateId, $isResident, $guestsCount, $date) ?? 0;
    }
}
