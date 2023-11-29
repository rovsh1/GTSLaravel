<?php

declare(strict_types=1);

namespace Module\Booking\EventSourcing\Domain\Repository;

use Illuminate\Database\Eloquent\Collection;
use Module\Booking\EventSourcing\Domain\ValueObject\EventGroupEnum;
use Module\Booking\EventSourcing\Infrastructure\Model\BookingHistory;
use Sdk\Booking\ValueObject\BookingId;

interface HistoryRepositoryInterface
{
    public function register(
        BookingId $bookingId,
        EventGroupEnum $event,
        array|null $payload,
        array $context = []
    ): void;

    /**
     * @param int $bookingId
     * @return Collection<int, BookingHistory>
     */
    public function getStatusHistory(int $bookingId): Collection;
}
