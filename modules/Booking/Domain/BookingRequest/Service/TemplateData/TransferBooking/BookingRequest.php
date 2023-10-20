<?php

namespace Module\Booking\Domain\BookingRequest\Service\TemplateData\TransferBooking;

use Module\Booking\Domain\BookingRequest\Service\Dto\ServiceDto;
use Module\Booking\Domain\BookingRequest\Service\TemplateDataInterface;

final class BookingRequest implements TemplateDataInterface
{
    public function __construct(
        private readonly ServiceDto $serviceDto,
    ) {}

    public function toArray(): array
    {
        return [
            'service' => $this->serviceDto,
            'guestsCount' => '',//посчитать всех людей в машине,
            'meetingTablet'=>'',
            'meetingTablet'=>'',
        ];
    }
}
