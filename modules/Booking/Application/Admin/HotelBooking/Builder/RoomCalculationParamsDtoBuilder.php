<?php

namespace Module\Booking\Application\Admin\HotelBooking\Builder;

use Module\Booking\Domain\HotelBooking\Entity\RoomBooking;
use Module\Pricing\Application\Dto\RoomCalculationParamsDto;

class RoomCalculationParamsDtoBuilder
{
    private RoomBooking $roomBooking;

    public function __construct()
    {
    }

    public function room(RoomBooking $roomBooking): static
    {
        $this->roomBooking = $roomBooking;

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