<?php

declare(strict_types=1);

namespace Module\Booking\Infrastructure\Repository;

use Illuminate\Database\Eloquent\Collection;
use Module\Booking\Domain\Shared\Event\BookingEventInterface;
use Module\Booking\Domain\Shared\Event\BookingRequestEventInterface;
use Module\Booking\Domain\Shared\Event\BookingStatusEventInterface;
use Module\Booking\Domain\Shared\Repository\BookingChangesLogRepositoryInterface;
use Module\Booking\Infrastructure\Models\BookingChangesLog;
use Module\Booking\Infrastructure\Models\EventTypeEnum;
use Module\Shared\Contracts\Service\ApplicationContextInterface;

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
            'order_id' => $event->orderId()->value(),
            'booking_id' => $event->bookingId()->value(),
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
