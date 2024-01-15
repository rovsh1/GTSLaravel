<?php

declare(strict_types=1);

namespace Pkg\Booking\EventSourcing\Infrastructure\Service;

use Illuminate\Database\Eloquent\Collection;
use Pkg\Booking\EventSourcing\Domain\Service\HistoryStorageInterface;
use Pkg\Booking\EventSourcing\Domain\ValueObject\EventGroupEnum;
use Pkg\Booking\EventSourcing\Infrastructure\Model\BookingHistory;
use Sdk\Booking\ValueObject\BookingId;

class HistoryStorage implements HistoryStorageInterface
{
    public function register(
        BookingId $bookingId,
        EventGroupEnum|null $group,
        string|null $field,
        string $description,
        mixed $before,
        mixed $after,
        array $context = []
    ): void {
        BookingHistory::create([
            'booking_id' => $bookingId->value(),
            'group' => $group?->name,
            'field' => $field,
            'description' => $description,
            'before' => is_array($before) ? json_encode($before) : $before,
            'after' => is_array($after) ? json_encode($after) : $after,
            //'order_id' => $event->orderId()->value(),
            'context' => $context
        ]);
    }

    public function getHistory(int $bookingId): Collection
    {
        return BookingHistory::whereBookingId($bookingId)
            ->orderBy('id', 'desc')
            ->get();
    }

    /**
     * @param int $bookingId
     * @return Collection<int, BookingHistory>
     */
    public function getStatusHistory(int $bookingId): Collection
    {
        return BookingHistory::whereBookingId($bookingId)
            ->whereGroup(EventGroupEnum::STATUS_UPDATED)
            ->orderBy('id', 'desc')
            ->get();
    }
}
