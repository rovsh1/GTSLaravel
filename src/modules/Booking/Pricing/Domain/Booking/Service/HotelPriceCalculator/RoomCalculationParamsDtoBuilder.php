<?php

namespace Module\Booking\Pricing\Domain\Booking\Service\HotelPriceCalculator;

use Module\Booking\Shared\Domain\Booking\Entity\HotelRoomBooking;
use Module\Booking\Shared\Domain\Booking\Repository\RoomBookingRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\RoomBookingId;
use Module\Hotel\Pricing\Application\Dto\RoomCalculationParamsDto;

class RoomCalculationParamsDtoBuilder
{
    private HotelRoomBooking $roomBooking;

    private bool $isClientCalculation = false;

    public function __construct(
        private readonly RoomBookingRepositoryInterface $roomBookingRepository
    ) {}

    public function room(RoomBookingId $roomBookingId): static
    {
        $this->roomBooking = $this->roomBookingRepository->findOrFail($roomBookingId);

        return $this;
    }

    public function withClientMarkups(): static
    {
        $this->isClientCalculation = true;

        return $this;
    }

    public function build(): RoomCalculationParamsDto
    {
        $roomBooking = $this->roomBooking;
        $details = $roomBooking->details();

        $manualDayPrice = $roomBooking->prices()->supplierPrice()->manualDayValue();
        if ($this->isClientCalculation) {
            $manualDayPrice = $roomBooking->prices()->clientPrice()->manualDayValue();
        }

        return new RoomCalculationParamsDto(
            accommodationId: $roomBooking->id()->value(),
            roomId: $roomBooking->roomInfo()->id(),
            rateId: $details->rateId(),
            isResident: $details->isResident(),
            guestsCount: $roomBooking->guestsCount(),
            manualDayPrice: $manualDayPrice,
            earlyCheckinPercent: $details->earlyCheckIn()?->priceMarkup()->value(),
            lateCheckoutPercent: $details->lateCheckOut()?->priceMarkup()->value()
        );
    }
}
