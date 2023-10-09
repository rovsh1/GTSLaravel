<?php

namespace Module\Booking\Domain\Booking\Service\HotelBookingPriceCalculator;

use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Module\Booking\Domain\Booking\Booking;
use Module\Booking\Domain\Booking\Entity\HotelBooking;
use Module\Pricing\Application\RequestDto\CalculateHotelPriceRequestDto;
use Module\Shared\Enum\CurrencyEnum;

class CalculateHotelPriceRequestDtoBuilder
{
    private Booking $booking;

    private HotelBooking $details;

    public function __construct(
        private readonly RoomCalculationParamsDtoBuilder $roomCalculationParamsDtoBuilder,
    ) {
    }

    public function booking(Booking $booking, HotelBooking $details): static
    {
        $this->booking = $booking;
        $this->details = $details;

        return $this;
    }

    public function build(): CalculateHotelPriceRequestDto
    {
        $booking = $this->booking;
        $details = $this->details;
        //@todo add to booking
        $order = DB::table('orders')
            ->where('id', $booking->orderId()->value())
            ->first();

        return new CalculateHotelPriceRequestDto(
            hotelId: $details->hotelInfo()->id(),
            rooms: $this->buildRooms(),
            outCurrency: CurrencyEnum::from($order->currency),
            period: new CarbonPeriod(
                $details->bookingPeriod()->dateFrom(),
                $details->bookingPeriod()->dateTo(),
            ),
            clientId: (int)$order->client_id
        );
    }

    private function buildRooms(): array
    {
        $rooms = [];
        foreach ($this->details->roomBookings() as $roomId) {
            $rooms[] = $this->roomCalculationParamsDtoBuilder
                ->room($roomId)
                ->build();
        }

        return $rooms;
    }
}