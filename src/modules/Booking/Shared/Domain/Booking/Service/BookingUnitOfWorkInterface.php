<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Service;

use Module\Booking\Shared\Domain\Booking\Booking;
use Sdk\Booking\Contracts\Entity\BookingPartInterface;
use Sdk\Booking\Contracts\Entity\DetailsInterface;
use Sdk\Booking\ValueObject\BookingId;

interface BookingUnitOfWorkInterface
{
    public function findOrFail(BookingId $bookingId): Booking;

    public function getDetails(BookingId $bookingId): DetailsInterface;

    public function persist(Booking|BookingPartInterface $entity): void;

    public function touch(BookingId $bookingId): void;

    public function commiting(\Closure $callback): void;

    public function commit(): void;
}
