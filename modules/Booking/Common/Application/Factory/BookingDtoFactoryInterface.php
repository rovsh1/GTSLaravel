<?php

declare(strict_types=1);

namespace Module\Booking\Common\Application\Factory;

use Module\Booking\Common\Domain\Entity\BookingInterface;

interface BookingDtoFactoryInterface
{
    public function createFromEntity(BookingInterface $booking): mixed;
}
