<?php

namespace Module\Booking\PriceCalculator\Domain\Service\HotelBooking;

use Module\Booking\Hotel\Domain\Entity\Booking;
use Module\Booking\Order\Domain\Repository\OrderRepositoryInterface;
use Module\Booking\PriceCalculator\Domain\Adapter\ClientAdapterInterface;
use Module\Booking\PriceCalculator\Domain\Adapter\HotelAdapterInterface;
use Module\Hotel\Application\Dto\MarkupSettingsDto;
use Module\Shared\Domain\Adapter\ConstantAdapterInterface;

class VariablesBuilder
{
    public function __construct(
        private readonly ConstantAdapterInterface $constantAdapter,
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly HotelAdapterInterface $hotelAdapter,
        private readonly ClientAdapterInterface $clientAdapter
    ) {
    }

    public function build(
        Booking $hotelBooking,
        int $roomId,
        bool $isResident,
        int $guestsCount,
        ?int $earlyCheckInPercent,
        ?int $lateCheckOutPercent
    ): CalculateVariables {
        $markupDto = $this->hotelAdapter->getMarkupSettings($hotelBooking->hotelInfo()->id());

        return new CalculateVariables(
            $hotelBooking->period(),
            $roomId,
            $isResident,
            $guestsCount,
            $markupDto->vat,
            $this->calculateClientMarkupPercent($hotelBooking, $markupDto),
            $this->calculateTouristTax($markupDto->touristTax),
            $earlyCheckInPercent,
            $lateCheckOutPercent
        );
    }

    private function calculateClientMarkupPercent(Booking $hotelBooking, MarkupSettingsDto $markupDto): int
    {
        $order = $this->orderRepository->find($hotelBooking->orderId()->value());
        //TODO implement legal entities
        $legalId = null;//$order->legalId()?->value();
        if ($legalId) {
            $legal = $this->clientAdapter->find($order->clientId());

            return $markupDto->clientMarkups->{$legal->type};
        } else {
            return $markupDto->clientMarkups->individual;
        }
    }

    private function calculateTouristTax(int $taxPercent): float
    {
        return $this->constantAdapter->basicCalculatedValue() * $taxPercent / 100;
    }
}
