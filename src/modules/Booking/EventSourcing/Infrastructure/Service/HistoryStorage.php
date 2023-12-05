<?php

declare(strict_types=1);

namespace Module\Booking\EventSourcing\Infrastructure\Service;

use Illuminate\Database\Eloquent\Collection;
use Module\Booking\EventSourcing\Domain\Service\HistoryStorageInterface;
use Module\Booking\EventSourcing\Domain\ValueObject\EventGroupEnum;
use Module\Booking\EventSourcing\Infrastructure\Model\BookingHistory;
use Sdk\Booking\ValueObject\BookingId;

class HistoryStorage implements HistoryStorageInterface
{
    public function register(
        BookingId $bookingId,
        EventGroupEnum $group,
        array|null $payload,
        array $context = []
    ): void {
        BookingHistory::create([
            'booking_id' => $bookingId->value(),
            'group' => $group->name,
            'payload' => $payload,
            //'order_id' => $event->orderId()->value(),
            'context' => $context
        ]);
    }

    public function getHistory(int $bookingId): Collection
    {
        return BookingHistory::whereBookingId($bookingId)
            ->orderBy('created_at', 'desc')
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
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
