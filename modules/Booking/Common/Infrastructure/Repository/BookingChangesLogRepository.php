<?php

declare(strict_types=1);

namespace Module\Booking\Common\Infrastructure\Repository;

use App\Core\Support\Facades\AppContext;
use Module\Booking\Common\Domain\Event\BookingEventInterface;
use Module\Booking\Common\Domain\Event\RequestBookingEventInterface;
use Module\Booking\Common\Domain\Event\StatusBookingEventInterface;
use Module\Booking\Common\Domain\Repository\BookingChangesLogRepositoryInterface;
use Module\Booking\Common\Domain\ValueObject\StatusChangeEvent;
use Module\Booking\Common\Infrastructure\Models\BookingChangesLog;
use Module\Booking\Common\Infrastructure\Models\EventTypeEnum;

class BookingChangesLogRepository implements
    BookingChangesLogRepositoryInterface
{
    public function __construct() {}

    public function logBookingChange(BookingEventInterface $event, array $context): void
    {
        $eventType = $this->getEventType($event);
        BookingChangesLog::create([
            'event' => $event::class,
            'event_type' => $eventType,
            'payload' => [],
            'order_id' => $event->orderId(),
            'booking_id' => $event->bookingId(),
            'context' => AppContext::get()
        ]);
    }

    /**
     * @param int $bookingId
     * @return StatusChangeEvent[]
     */
    public function getStatusHistory(int $bookingId): array
    {
        return BookingChangesLog::whereBookingId($bookingId)->get()->map(
            fn(BookingChangesLog $eventModel) => new StatusChangeEvent(
                \Str::afterLast($eventModel->event, '\\'),
                1,
                1,
                $eventModel->created_at->toImmutable()
            )
        )->all();
    }

    private function getEventType(BookingEventInterface $event): EventTypeEnum
    {
        $eventType = EventTypeEnum::OTHER;
        if ($event instanceof StatusBookingEventInterface) {
            $eventType = EventTypeEnum::STATUS;
        }
        if ($event instanceof RequestBookingEventInterface) {
            $eventType = EventTypeEnum::REQUEST;
        }

        return $eventType;
    }
}
