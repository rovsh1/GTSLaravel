<?php

declare(strict_types=1);

namespace Module\Booking\Common\Application\Factory;

use Module\Booking\Common\Application\Service\StatusStorage;
use Module\Booking\Common\Domain\Entity\BookingInterface;

abstract class AbstractBookingDtoFactory implements BookingDtoFactoryInterface
{
    public function __construct(protected readonly StatusStorage $statusStorage) {}

    abstract public function createFromEntity(BookingInterface $booking): mixed;
}
