<?php

declare(strict_types=1);

namespace Module\Booking\Domain\ServiceBooking\Service\DetailsCreator;

use Module\Booking\Domain\ServiceBooking\Entity\ServiceDetailsInterface;
use Module\Booking\Domain\ServiceBooking\ValueObject\BookingId;
use Module\Supplier\Application\Response\ServiceDto;

interface ProcessorInterface
{
    public function process(BookingId $bookingId, ServiceDto $service, array $detailsData): ServiceDetailsInterface;
}
