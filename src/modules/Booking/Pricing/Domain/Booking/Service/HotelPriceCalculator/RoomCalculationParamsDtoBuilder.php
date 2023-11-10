<?php

namespace Module\Booking\Pricing\Domain\Booking\Service\HotelPriceCalculator;

use Module\Booking\Shared\Domain\Booking\Entity\HotelAccommodation;
use Module\Booking\Shared\Domain\Booking\Repository\AccommodationRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\AccommodationId;
use Module\Hotel\Pricing\Application\Dto\RoomCalculationParamsDto;

class RoomCalculationParamsDtoBuilder
{
    private HotelAccommodation $accommodation;

    private bool $isClientCalculation = false;

    public function __construct(
        private readonly AccommodationRepositoryInterface $accommodationRepository
    ) {}

    public function room(AccommodationId $accommodationId): static
    {
        $this->accommodation = $this->accommodationRepository->findOrFail($accommodationId);

        return $this;
    }

    public function withClientMarkups(): static
    {
        $this->isClientCalculation = true;

        return $this;
    }

    public function build(): RoomCalculationParamsDto
    {
        $accommodation = $this->accommodation;
        $details = $accommodation->details();

        $manualDayPrice = $accommodation->prices()->supplierPrice()->manualDayValue();
        if ($this->isClientCalculation) {
            $manualDayPrice = $accommodation->prices()->clientPrice()->manualDayValue();
        }

        return new RoomCalculationParamsDto(
            accommodationId: $accommodation->id()->value(),
            roomId: $accommodation->roomInfo()->id(),
            rateId: $details->rateId(),
            isResident: $details->isResident(),
            guestsCount: $accommodation->guestsCount(),
            manualDayPrice: $manualDayPrice,
            earlyCheckinPercent: $details->earlyCheckIn()?->priceMarkup()->value(),
            lateCheckoutPercent: $details->lateCheckOut()?->priceMarkup()->value()
        );
    }
}
