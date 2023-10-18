<?php

namespace Module\Booking\Domain\BookingRequest\Service;

interface TemplateDataInterface
{
    public function toArray(): array;
}