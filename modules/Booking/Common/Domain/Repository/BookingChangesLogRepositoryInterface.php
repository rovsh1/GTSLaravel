<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Repository;

use Module\Booking\Common\Domain\Event\BookingEventInterface;
use Module\Booking\Common\Domain\ValueObject\StatusChangeEvent;

interface BookingChangesLogRepositoryInterface
{
    public function logBookingChange(BookingEventInterface $event, array $context): void;

    /**
     * @param int $bookingId
     * @return StatusChangeEvent[]
     */
    public function getStatusHistory(int $bookingId): array;
}
