<?php

declare(strict_types=1);

namespace Module\Booking\EventSourcing\Infrastructure\Repository;

use Illuminate\Database\Eloquent\Collection;
use Module\Booking\EventSourcing\Domain\Repository\HistoryRepositoryInterface;
use Module\Booking\EventSourcing\Domain\ValueObject\EventGroupEnum;
use Module\Booking\EventSourcing\Infrastructure\Model\BookingHistory;
use Sdk\Booking\ValueObject\BookingId;

class HistoryRepository implements HistoryRepositoryInterface
{
    public function register(
        BookingId $bookingId,
        EventGroupEnum $event,
        array|null $payload,
        array $context = []
    ): void {
        BookingHistory::create([
            'booking_id' => $bookingId->value(),
            'event' => $event->name,
            'payload' => $payload,
            //'order_id' => $event->orderId()->value(),
            'context' => $context,
        ]);
    }

    /**
     * @param int $bookingId
     * @return Collection<int, BookingHistory>
     */
    public function getStatusHistory(int $bookingId): Collection
    {
        return BookingHistory::whereBookingId($bookingId)
            ->whereGroup(EventGroupEnum::STATUS_UPDATED)
            ->get();
    }
}
