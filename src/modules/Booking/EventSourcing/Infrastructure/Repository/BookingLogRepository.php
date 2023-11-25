<?php

declare(strict_types=1);

namespace Module\Booking\EventSourcing\Infrastructure\Repository;

use Illuminate\Database\Eloquent\Collection;
use Module\Booking\EventSourcing\Domain\Repository\BookingLogRepositoryInterface;
use Module\Booking\EventSourcing\Domain\ValueObject\BookingEventEnum;
use Module\Booking\EventSourcing\Infrastructure\Model\BookingEventLog;
use Sdk\Booking\ValueObject\BookingId;

class BookingLogRepository implements BookingLogRepositoryInterface
{
    public function register(
        BookingId $bookingId,
        BookingEventEnum $event,
        array|null $payload,
        array $context = []
    ): void {
        BookingEventLog::create([
            'booking_id' => $bookingId->value(),
            'event' => $event->name,
            'payload' => $payload,
            //'order_id' => $event->orderId()->value(),
            'context' => $context,
        ]);
    }

    /**
     * @param int $bookingId
     * @return Collection<int, BookingEventLog>
     */
    public function getStatusHistory(int $bookingId): Collection
    {
        return BookingEventLog::whereBookingId($bookingId)
            ->whereEvent(BookingEventEnum::STATUS_UPDATED->name)
            ->get();
    }
}
