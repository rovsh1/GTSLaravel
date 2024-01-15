<?php

namespace Pkg\Booking\Requesting\Domain\Factory;

use Module\Booking\Shared\Domain\Booking\Booking;
use Pkg\Booking\Requesting\Domain\Entity\BookingRequest;
use Sdk\Booking\Enum\RequestTypeEnum;

interface RequestFactoryInterface
{
    public function generate(Booking $booking, RequestTypeEnum $requestType): BookingRequest;
}
