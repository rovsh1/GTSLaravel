<?php

declare(strict_types=1);

namespace Module\Booking\ChangeHistory\Domain\Repository;

use Illuminate\Database\Eloquent\Collection;
use Module\Booking\ChangeHistory\Infrastructure\Model\BookingChangesLog;
use Module\Booking\Shared\Domain\Shared\Event\BookingEventInterface;

interface BookingChangesLogRepositoryInterface
{
    public function logBookingChange(BookingEventInterface $event, array $context = []): void;

    /**
     * @param int $bookingId
     * @return Collection<int, BookingChangesLog>
     */
    public function getStatusHistory(int $bookingId): Collection;
}
