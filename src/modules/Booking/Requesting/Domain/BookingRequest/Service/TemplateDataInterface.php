<?php

namespace Module\Booking\Requesting\Domain\BookingRequest\Service;

interface TemplateDataInterface
{
    public function toArray(): array;
}