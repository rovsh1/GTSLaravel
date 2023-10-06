<?php

declare(strict_types=1);

namespace Module\Booking\Application\Shared\Factory;

use Module\Booking\Application\Shared\Service\StatusStorage;
use Module\Booking\Domain\Shared\Entity\BookingInterface;

abstract class AbstractBookingDtoFactory implements BookingDtoFactoryInterface
{
    public function __construct(protected readonly StatusStorage $statusStorage) {}

    abstract public function createFromEntity(BookingInterface $booking): mixed;
}
