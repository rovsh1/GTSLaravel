<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\ServiceBooking\Service\DetailsCreator;

use Module\Booking\Domain\ServiceBooking\Entity\ServiceDetailsInterface;
use Module\Booking\Domain\ServiceBooking\ValueObject\BookingId;
use Module\Booking\Domain\ServiceBooking\ValueObject\ServiceId;

interface ProcessorInterface
{
    public function process(BookingId $bookingId, ServiceId $serviceId, array $detailsData): ServiceDetailsInterface;
}
