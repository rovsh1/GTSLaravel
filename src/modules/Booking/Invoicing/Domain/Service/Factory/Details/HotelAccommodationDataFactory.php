<?php

declare(strict_types=1);

namespace Module\Booking\Invoicing\Domain\Service\Factory\Details;

use Illuminate\Support\Collection;
use Module\Booking\Invoicing\Domain\Service\Dto\Service\PriceDto;
use Module\Booking\Invoicing\Domain\Service\Dto\ServiceInfoDto;
use Module\Booking\Invoicing\Domain\Service\Factory\BookingPeriodDataFactory;
use Module\Booking\Invoicing\Domain\Service\Factory\GuestDataFactory;
use Module\Booking\Shared\Domain\Booking\Adapter\HotelRoomAdapterInterface;
use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Repository\DetailsRepositoryInterface;
use Module\Shared\Support\Dto\DetailOptionDto;
use Sdk\Booking\Entity\Details\HotelBooking;
use Sdk\Booking\Entity\HotelAccommodation;
use Sdk\Booking\ValueObject\HotelBooking\Condition;
use Sdk\Shared\Enum\CurrencyEnum;

class HotelAccommodationDataFactory
{
    public function __construct(
        private readonly DetailsRepositoryInterface $detailsRepository,
        private readonly BookingPeriodDataFactory $bookingPeriodDataFactory,
        private readonly HotelRoomAdapterInterface $hotelRoomAdapter,
        private readonly GuestDataFactory $guestDataFactory,
    ) {}

    public function build(Booking $booking, HotelAccommodation $accommodation): ServiceInfoDto
    {
        /** @var HotelBooking $details */
        $details = $this->detailsRepository->findOrFail($booking->id());
        $serviceTitle = __('Отель: :hotel', ['hotel' => $details->hotelInfo()->name()]);

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
        $checkInTime = $accommodation->details()->earlyCheckIn() !== null
            ? $this->buildConditionDetailOption($accommodation->details()->earlyCheckIn())
            : __('с :time', ['time' => $details->hotelInfo()->checkInTime()->value()]);

        $checkOutTime = $accommodation->details()->lateCheckOut() !== null
            ? $this->buildConditionDetailOption($accommodation->details()->lateCheckOut())
            : __('до :time', ['time' => $details->hotelInfo()->checkOutTime()->value()]);

        $room = $this->hotelRoomAdapter->findById($accommodation->roomInfo()->id());

        return collect([
            DetailOptionDto::createDate('Дата заезда', $details->bookingPeriod()->dateFrom()),
            DetailOptionDto::createText('Время заезда', $checkInTime),
            DetailOptionDto::createDate('Дата выезда', $details->bookingPeriod()->dateTo()),
            DetailOptionDto::createText('Время выезда', $checkOutTime),
            DetailOptionDto::createNumber('Ночей', $details->bookingPeriod()->nightsCount()),
            DetailOptionDto::createText('Номер', $room->name),
        ]);
    }

    private function buildConditionDetailOption(Condition $condition): string
    {
        $timePeriod = __(
            'с :from по :to',
            ['from' => $condition->timePeriod()->from(), 'to' => $condition->timePeriod()->to()],
        );
        $markup = " (+{$condition->priceMarkup()->value()}%)";

        return "{$timePeriod} {$markup}";
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
