<?php

namespace Module\Booking\Domain\BookingRequest\Service;

interface TemplateDataInterface
{
    public function bookingRequestData(): array;

    public function changeRequestData(): array;

    public function cancellationRequestData(): array;
}