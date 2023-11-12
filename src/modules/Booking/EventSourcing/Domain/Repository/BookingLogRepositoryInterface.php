<?php

declare(strict_types=1);

namespace Module\Booking\EventSourcing\Domain\Repository;

use Illuminate\Database\Eloquent\Collection;
use Module\Booking\EventSourcing\Domain\ValueObject\BookingEventEnum;
use Module\Booking\EventSourcing\Infrastructure\Model\BookingEventLog;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;

interface BookingLogRepositoryInterface
{
    public function register(
        BookingId $bookingId,
        BookingEventEnum $event,
        array|null $payload,
        array $context = []
    ): void;

    /**
     * @param int $bookingId
     * @return Collection<int, BookingEventLog>
     */
    public function getStatusHistory(int $bookingId): Collection;
}
