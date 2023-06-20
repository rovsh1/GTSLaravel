<?php

namespace Module\Booking\PriceCalculator\Domain\Service\HotelBooking;

use DateTime;
use Module\Booking\Hotel\Domain\Entity\Booking;
use Module\Booking\Hotel\Domain\Repository\BookingRepositoryInterface;
use Module\Booking\Hotel\Domain\ValueObject\RoomPrice;
use Module\Booking\Order\Domain\Repository\OrderRepositoryInterface;
use Module\Booking\PriceCalculator\Domain\Adapter\ClientAdapterInterface;
use Module\Booking\PriceCalculator\Domain\Adapter\HotelAdapterInterface;
use Module\Booking\PriceCalculator\Domain\Service\HotelBooking\Formula\BORoomPriceFormula;
use Module\Booking\PriceCalculator\Domain\Service\HotelBooking\Formula\MarkupVariables;
use Module\Booking\PriceCalculator\Domain\Service\HotelBooking\Formula\HORoomPriceFormula;
use Module\Booking\PriceCalculator\Domain\Service\HotelBooking\Formula\RoomVariables;
use Module\Booking\PriceCalculator\Domain\Service\HotelBooking\Support\BookingDaysCollection;

class RoomCalculator
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly HotelAdapterInterface $hotelAdapter,
        private readonly ClientAdapterInterface $clientAdapter
    ) {
    }

    public function calculate(CalculateVariables $calculateVariables): RoomPrice
    {
        $hotelBooking = $this->bookingRepository->find($calculateVariables->bookingId);

        $markupVariables = $this->buildMarkupVariables($hotelBooking, $calculateVariables);
        $formulaVariables = new RoomVariables($calculateVariables->isResident, $calculateVariables->guestsCount, 1);
        $hoFormula = new HORoomPriceFormula($markupVariables, $formulaVariables);
        $boFormula = new BORoomPriceFormula($markupVariables, $formulaVariables);

        $daysCollection = $this->buildBookingDaysCollection($hotelBooking);

        $netValue = 0.0;
        $hoValue = 0.0;
        $boValue = 0.0;
        foreach ($daysCollection as $date) {
            $datePrice = $this->getDatePrice($calculateVariables->roomId, $date);
            $netValue += $datePrice;
            $hoValue += $hoFormula->calculate($datePrice);
            $boValue += $boFormula->calculate($datePrice);
        }
        $avgDailyValue = $netValue / $daysCollection->nightsCount();

        return new RoomPrice(
            $netValue,
            $avgDailyValue,
            $hoValue,
            $boValue
        );
    }

    private function buildMarkupVariables(
        Booking $hotelBooking,
        CalculateVariables $calculateVariables
    ): MarkupVariables {
        $markupDto = $this->hotelAdapter->getMarkupSettings($hotelBooking->hotelInfo()->id());
        $order = $this->orderRepository->find($hotelBooking->orderId()->value());
        $clientDto = $this->clientAdapter->find($order->clientId());
        $clientType = 'TO';

        return new MarkupVariables(
            $markupDto->vat,
            $markupDto->clientMarkups->$clientType,
            $calculateVariables->earlyCheckInPercent,
            $calculateVariables->lateCheckOutPercent,
            $markupDto->touristTax
        );
    }

    private function buildBookingDaysCollection(Booking $hotelBooking): BookingDaysCollection
    {
        return new BookingDaysCollection($hotelBooking->period()->includedDates());
    }

    private function getDatePrice(int $roomId, DateTime $date): float
    {
        return $this->hotelAdapter->getRoomPrice($roomId, $date) ?? 0;
    }
}
