<?php

namespace Module\Support\MailManager\Infrastructure\Service\DataBuilder\Booking;

use Module\Support\MailManager\Domain\Adapter\HotelBookingAdapterInterface;
use Module\Support\MailManager\Domain\Service\DataBuilder\Data\DataInterface;
use Module\Support\MailManager\Domain\Service\DataBuilder\Data\HotelBooking\BookingRequestData;
use Module\Support\MailManager\Domain\Service\DataBuilder\Dto\BookingRequestDataDto;
use Module\Support\MailManager\Infrastructure\Service\DataBuilder\Support\AbstractBookingDataBuilder;
use Module\Support\MailManager\Infrastructure\Service\DataBuilder\Support\HotelBookingDtoFactory as DtoFactory;

class HotelBookingDataBuilder extends AbstractBookingDataBuilder
{
    public function __construct(
        private readonly HotelBookingAdapterInterface $bookingAdapter
    ) {
    }

    protected function buildBookingRequestData(BookingRequestDataDto $dataDto): DataInterface
    {
        $bookingDto = $this->bookingAdapter->findOrFail($dataDto->bookingId);

        return new BookingRequestData(
            DtoFactory::makeBookingInfo($bookingDto),
            DtoFactory::makeRoomsArray($bookingDto)
        );
    }
}