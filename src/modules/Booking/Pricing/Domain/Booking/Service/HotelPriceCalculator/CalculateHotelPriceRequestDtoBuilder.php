<?php

namespace Module\Booking\Pricing\Domain\Booking\Service\HotelPriceCalculator;

use Carbon\CarbonPeriod;
use Module\Booking\Shared\Domain\Booking\Adapter\HotelAdapterInterface;
use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Entity\HotelBooking;
use Module\Booking\Shared\Domain\Order\Order;
use Module\Booking\Shared\Domain\Order\Repository\OrderRepositoryInterface;
use Module\Booking\Shared\Domain\Order\ValueObject\ClientId;
use Module\Pricing\Application\RequestDto\CalculateHotelPriceRequestDto;
use Module\Shared\Enum\CurrencyEnum;

class CalculateHotelPriceRequestDtoBuilder
{
    private Booking $booking;

    private HotelBooking $details;

    private ClientId $clientId;

    private Order $order;

    public function __construct(
        private readonly RoomCalculationParamsDtoBuilder $roomCalculationParamsDtoBuilder,
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly HotelAdapterInterface $hotelAdapter,
    ) {}

    public function booking(Booking $booking, HotelBooking $details): static
    {
        $this->booking = $booking;
        $this->details = $details;

        return $this;
    }

    public function withClientMarkups(): static
    {
        $order = $this->getOrder();
        $this->clientId = $order->clientId();

        return $this;
    }

    public function build(): CalculateHotelPriceRequestDto
    {
        $details = $this->details;

        return new CalculateHotelPriceRequestDto(
            hotelId: $details->hotelInfo()->id(),
            rooms: $this->buildRooms(),
            outCurrency: $this->getOutCurrency(),
            period: new CarbonPeriod(
                $details->bookingPeriod()->dateFrom(),
                $details->bookingPeriod()->dateTo(),
            ),
            clientId: isset($this->clientId) ? $this->clientId->value() : null,
        );
    }

    private function buildRooms(): array
    {
        $rooms = [];
        foreach ($this->details->roomBookings() as $roomId) {
            $room = $this->roomCalculationParamsDtoBuilder->room($roomId);
            if (isset($this->clientId)) {
                $room->withClientMarkups();
            }
            $rooms[] = $room->build();
        }

        return $rooms;
    }

    private function getOutCurrency(): CurrencyEnum
    {
        if (isset($this->clientId)) {
            return $this->order->currency();
        }

        $hotelDto = $this->hotelAdapter->findById($this->details->hotelInfo()->id());

        return CurrencyEnum::from($hotelDto->currency);
    }

    private function getOrder(): Order
    {
        if (!isset($this->order)) {
            $this->order = $this->orderRepository->findOrFail($this->booking->orderId());
        }

        return $this->order;
    }
}
