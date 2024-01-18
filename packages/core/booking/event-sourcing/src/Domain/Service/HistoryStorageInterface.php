<?php

declare(strict_types=1);

namespace Pkg\Booking\EventSourcing\Domain\Service;

use Illuminate\Database\Eloquent\Collection;
use Pkg\Booking\EventSourcing\Domain\ValueObject\EventGroupEnum;
use Pkg\Booking\EventSourcing\Infrastructure\Model\BookingHistory;
use Sdk\Booking\ValueObject\BookingId;

interface HistoryStorageInterface
{
    public function register(
        BookingId $bookingId,
        EventGroupEnum|null $group,
        string|null $field,
        string $description,
        mixed $before,
        mixed $after,
        array $context = []
    ): void;

    /**
     * @param int $bookingId
     * @return Collection<int, BookingHistory>
     */
    public function getHistory(int $bookingId): Collection;

    /**
     * @param int $bookingId
     * @return Collection<int, BookingHistory>
     */
    public function getStatusHistory(int $bookingId): Collection;
}