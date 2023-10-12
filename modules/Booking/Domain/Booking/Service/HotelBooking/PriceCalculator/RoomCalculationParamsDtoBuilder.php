<?php

namespace Module\Booking\Domain\Booking\Service\HotelBooking\PriceCalculator;

use Module\Booking\Domain\Booking\Entity\HotelRoomBooking;
use Module\Booking\Domain\Booking\Repository\RoomBookingRepositoryInterface;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\RoomBookingId;
use Module\Pricing\Application\Dto\RoomCalculationParamsDto;

class RoomCalculationParamsDtoBuilder
{
    private HotelRoomBooking $roomBooking;

    public function __construct(
        private readonly RoomBookingRepositoryInterface $roomBookingRepository
    ) {
    }

    public function room(RoomBookingId $roomBookingId): static
    {
        $this->roomBooking = $this->roomBookingRepository->findOrFail($roomBookingId);

        return $this;
    }

    public function build(): RoomCalculationParamsDto
    {
        $roomBooking = $this->roomBooking;
        $details = $roomBooking->details();

        return new RoomCalculationParamsDto(
            accommodationId: $roomBooking->id()->value(),
            roomId: $roomBooking->roomInfo()->id(),
            rateId: $details->rateId(),
            isResident: $details->isResident(),
            guestsCount: $roomBooking->guestsCount(),
            manualDayPrice: null,
            earlyCheckinPercent: $details->earlyCheckIn()?->priceMarkup()->value(),
            lateCheckoutPercent: $details->lateCheckOut()?->priceMarkup()->value()
        );
    }
}
