<?php

declare(strict_types=1);

namespace Module\Booking\Application\Shared\Factory;

use Module\Booking\Domain\Shared\Entity\BookingInterface;

interface BookingDtoFactoryInterface
{
    public function createFromEntity(BookingInterface $booking): mixed;
}
