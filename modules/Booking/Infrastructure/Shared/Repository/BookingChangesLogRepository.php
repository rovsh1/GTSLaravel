<?php

declare(strict_types=1);

namespace Module\Booking\Infrastructure\Shared\Repository;

use Illuminate\Database\Eloquent\Collection;
use Module\Booking\Domain\Shared\Event\BookingEventInterface;
use Module\Booking\Domain\Shared\Event\BookingRequestEventInterface;
use Module\Booking\Domain\Shared\Event\BookingStatusEventInterface;
use Module\Booking\Domain\Shared\Repository\BookingChangesLogRepositoryInterface;
use Module\Booking\Infrastructure\Shared\Models\BookingChangesLog;
use Module\Booking\Infrastructure\Shared\Models\EventTypeEnum;
use Module\Shared\Domain\Service\ApplicationContextInterface;

class BookingChangesLogRepository implements
    BookingChangesLogRepositoryInterface
{
    public function __construct(
        private readonly ApplicationContextInterface $contextService
    ) {
    }

    public function logBookingChange(BookingEventInterface $event, array $context = []): void
    {
        $eventType = $this->getEventType($event);
        BookingChangesLog::create([
            'event' => $event::class,
            'event_type' => $eventType,
            'payload' => $event->payload(),
            'order_id' => $event->orderId(),
            'booking_id' => $event->bookingId(),
            'context' => $this->contextService->toArray($context),
        ]);
    }

    /**
     * @param int $bookingId
     * @return Collection<int, BookingChangesLog>
     */
    public function getStatusHistory(int $bookingId): Collection
    {
        return BookingChangesLog::whereBookingId($bookingId)->whereEventType(EventTypeEnum::STATUS)->get();
    }

    private function getEventType(BookingEventInterface $event): EventTypeEnum
    {
        $eventType = EventTypeEnum::OTHER;
        if ($event instanceof BookingStatusEventInterface) {
            $eventType = EventTypeEnum::STATUS;
        }
        if ($event instanceof BookingRequestEventInterface) {
            $eventType = EventTypeEnum::REQUEST;
        }

        return $eventType;
    }
}
