<?php

namespace Module\Booking\Pricing\Domain\Booking\Service\HotelPriceCalculator;

use Carbon\CarbonPeriod;
use Module\Booking\Shared\Domain\Booking\Adapter\HotelAdapterInterface;
use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Entity\HotelBooking;
use Module\Booking\Shared\Domain\Booking\Repository\AccommodationRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\DetailsRepositoryInterface;
use Module\Booking\Shared\Domain\Order\Order;
use Module\Booking\Shared\Domain\Order\Repository\OrderRepositoryInterface;
use Module\Booking\Shared\Domain\Order\ValueObject\ClientId;
use Module\Hotel\Pricing\Application\RequestDto\CalculateHotelPriceRequestDto;
use Module\Shared\Enum\CurrencyEnum;

class CalculateHotelPriceRequestDtoBuilder
{
    private Booking $booking;

    private HotelBooking $details;

    private ClientId $clientId;

    private Order $order;

    public function __construct(
        private readonly AccommodationRepositoryInterface $accommodationRepository,
        private readonly RoomCalculationParamsDtoBuilder $roomCalculationParamsDtoBuilder,
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly HotelAdapterInterface $hotelAdapter,
        private readonly DetailsRepositoryInterface $detailsRepository,
    ) {
    }

    public function booking(Booking $booking): static
    {
        $details = $this->detailsRepository->findOrFail($booking->id());
        assert($details instanceof HotelBooking);
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
        return new CalculateHotelPriceRequestDto(
            hotelId: $this->details->hotelInfo()->id(),
            rooms: $this->buildRooms(),
            outCurrency: $this->getOutCurrency(),
            period: new CarbonPeriod(
                $this->details->bookingPeriod()->dateFrom(),
                $this->details->bookingPeriod()->dateTo(),
            ),
            clientId: isset($this->clientId) ? $this->clientId->value() : null,
        );
    }

    private function buildRooms(): array
    {
        $rooms = [];
        foreach ($this->accommodationRepository->getByBookingId($this->booking->id()) as $accommodation) {
            $this->roomCalculationParamsDtoBuilder->accommodation($accommodation);
            if (isset($this->clientId)) {
                $this->roomCalculationParamsDtoBuilder->withClientMarkups();
            }
            $rooms[] = $this->roomCalculationParamsDtoBuilder->build();
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
