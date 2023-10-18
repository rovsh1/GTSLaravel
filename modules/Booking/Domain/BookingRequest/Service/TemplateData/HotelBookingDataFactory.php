<?php

namespace Module\Booking\Domain\BookingRequest\Service\TemplateData;

use Module\Booking\Domain\Booking\Booking;
use Module\Booking\Domain\BookingRequest\Service\TemplateDataInterface;
use Module\Booking\Domain\BookingRequest\ValueObject\RequestTypeEnum;

class HotelBookingDataFactory
{
    public function __construct()
    {
    }

    public function build(Booking $booking, RequestTypeEnum $requestType): TemplateDataInterface
    {
        return match ($requestType) {
            RequestTypeEnum::BOOKING => new HotelBooking\BookingRequest(),
            RequestTypeEnum::CHANGE => throw new \Exception('To be implemented'),
            RequestTypeEnum::CANCEL => throw new \Exception('To be implemented')
        };
    }
}