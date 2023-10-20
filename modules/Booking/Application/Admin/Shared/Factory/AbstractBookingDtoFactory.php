<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\Shared\Factory;

use Module\Booking\Domain\Shared\Entity\BookingInterface;

abstract class AbstractBookingDtoFactory implements BookingDtoFactoryInterface
{
    public function __construct(protected readonly StatusDtoFactory $statusDtoFactory) {}

    abstract public function createFromEntity(BookingInterface $booking): mixed;
}
