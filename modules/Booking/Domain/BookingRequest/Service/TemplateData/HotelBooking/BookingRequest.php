<?php

namespace Module\Booking\Domain\BookingRequest\Service\TemplateData\HotelBooking;

use Module\Booking\Domain\BookingRequest\Service\TemplateDataInterface;

final class BookingRequest implements TemplateDataInterface
{
    public function __construct(
        private readonly array $data,
    ) {}

    public function toArray(): array
    {
        return [
            ...$this->data
        ];
    }
}
