<?php

declare(strict_types=1);

namespace Module\Booking\Common\Infrastructure\Repository;

use Illuminate\Database\Eloquent\Collection;
use Module\Booking\Common\Domain\Event\BookingEventInterface;
use Module\Booking\Common\Domain\Event\BookingRequestEventInterface;
use Module\Booking\Common\Domain\Event\BookingStatusEventInterface;
use Module\Booking\Common\Domain\Repository\BookingChangesLogRepositoryInterface;
use Module\Booking\Common\Infrastructure\Models\BookingChangesLog;
use Module\Booking\Common\Infrastructure\Models\EventTypeEnum;
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
