<?php

declare(strict_types=1);

namespace Module\Booking\Invoicing\Domain\Service\Factory\Details;

use Illuminate\Support\Collection;
use Module\Booking\Invoicing\Domain\Service\Dto\Booking\DetailOptionDto;
use Module\Booking\Invoicing\Domain\Service\Dto\Booking\PriceDto;
use Module\Booking\Invoicing\Domain\Service\Dto\ServiceInfoDto;
use Module\Booking\Invoicing\Domain\Service\Factory\BookingPeriodDataFactory;
use Module\Booking\Invoicing\Domain\Service\Factory\GuestDataFactory;
use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Repository\DetailsRepositoryInterface;
use Sdk\Booking\Entity\Details\HotelBooking;
use Sdk\Booking\Entity\HotelAccommodation;
use Sdk\Shared\Enum\CurrencyEnum;

class HotelAccommodationDataFactory
{
    public function __construct(
        private readonly DetailsRepositoryInterface $detailsRepository,
        private readonly BookingPeriodDataFactory $bookingPeriodDataFactory,
        private readonly GuestDataFactory $guestDataFactory,
    ) {}

    public function build(Booking $booking, HotelAccommodation $accommodation): ServiceInfoDto
    {
        /** @var HotelBooking $details */
        $details = $this->detailsRepository->findOrFail($booking->id());
        $serviceTitle = "Отель: {$details->hotelInfo()->name()}";

        return new ServiceInfoDto(
            title: $serviceTitle,
            bookingPeriod: $this->bookingPeriodDataFactory->build($details),
            detailOptions: $this->buildDetails($details, $accommodation),
            guests: $this->guestDataFactory->build($accommodation->guestIds()),
            price: $this->buildPrice($booking->prices()->clientPrice()->currency(), $accommodation),
        );
    }

    private function buildDetails(HotelBooking $details, HotelAccommodation $accommodation): Collection
    {
        $checkInTime = $details->hotelInfo()->checkInTime()->value();
        if ($accommodation->details()->earlyCheckIn() !== null) {
            $checkInTime = $accommodation->details()->earlyCheckIn()->timePeriod()->from();
        }
        $checkOutTime = $details->hotelInfo()->checkOutTime()->value();
        if ($accommodation->details()->lateCheckOut() !== null) {
            $checkOutTime = $accommodation->details()->lateCheckOut()->timePeriod()->to();
        }

        return collect([
            DetailOptionDto::createDate('Дата заезда', $details->bookingPeriod()->dateFrom()),
            DetailOptionDto::createTime('Время заезда', $checkInTime),
            DetailOptionDto::createDate('Дата выезда', $details->bookingPeriod()->dateTo()),
            DetailOptionDto::createTime('Время выезда', $checkOutTime),
            DetailOptionDto::createNumber('Ночей', $details->bookingPeriod()->nightsCount()),
            DetailOptionDto::createText('Номер', $accommodation->roomInfo()->name()),
        ]);
    }

    private function buildPrice(CurrencyEnum $currency, HotelAccommodation $accommodation): PriceDto
    {
        $clientPrice = $accommodation->prices()->clientPrice();
        $amount = $clientPrice->manualValue() ?? $clientPrice->value();

        return new PriceDto(
            1,
            $amount,
            $amount,
            $currency->name,
            null,//@todo штраф за номер?
        );
    }
}
