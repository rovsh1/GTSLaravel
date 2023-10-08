<?php

namespace Module\Booking\Application\Admin\HotelBooking\Builder;

use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Module\Booking\Domain\HotelBooking\HotelBooking;
use Module\Booking\Domain\HotelBooking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\ServiceBooking\ValueObject\BookingId;
use Module\Pricing\Application\RequestDto\CalculateHotelRoomsPriceRequestDto;
use Module\Shared\Enum\CurrencyEnum;

class CalculateHotelPriceRequestDtoBuilder
{
    private HotelBooking $booking;

    public function __construct(
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly RoomCalculationParamsDtoBuilder $roomCalculationParamsDtoBuilder
    ) {
    }

    public function booking(BookingId|HotelBooking $booking): static
    {
        if ($booking instanceof BookingId) {
            $this->booking = $this->bookingRepository->findOrFail($booking);
        } else {
            $this->booking = $booking;
        }

        return $this;
    }

    public function build(): CalculateHotelRoomsPriceRequestDto
    {
        $booking = $this->booking;
        //@todo add to booking
        $order = DB::table('orders')
            ->where('id', $booking->orderId()->value())
            ->first();

        return new CalculateHotelRoomsPriceRequestDto(
            hotelId: $booking->hotelInfo()->id(),
            rooms: $this->buildRooms(),
            outCurrency: CurrencyEnum::from($order->currency),
            period: new CarbonPeriod(
                $booking->period()->dateFrom(),
                $booking->period()->dateTo(),
            ),
            clientId: (int)$order->client_id
        );
    }

    private function buildRooms(): array
    {
        $rooms = [];
        foreach ($this->booking->roomBookings() as $room) {
            $rooms[] = $this->roomCalculationParamsDtoBuilder
                ->room($room)
                ->build();
        }

        return $rooms;
    }
}