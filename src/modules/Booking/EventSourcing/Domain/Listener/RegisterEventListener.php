<?php

namespace Module\Booking\EventSourcing\Domain\Listener;

use Module\Booking\EventSourcing\Domain\Service\HistoryStorageInterface;
use Module\Booking\EventSourcing\Domain\ValueObject\EventGroupEnum;
use Sdk\Booking\IntegrationEvent\PriceChanged;
use Sdk\Booking\IntegrationEvent\RequestSent;
use Sdk\Booking\IntegrationEvent\StatusUpdated;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Module\Contracts\Event\IntegrationEventInterface;
use Sdk\Module\Contracts\Event\IntegrationEventListenerInterface;
use Sdk\Module\Event\IntegrationEventMessage;
use Sdk\Module\Services\IntegrationEventSerializer;

class RegisterEventListener implements IntegrationEventListenerInterface
{
    public function __construct(
        private readonly HistoryStorageInterface $historyStorage,
        private readonly IntegrationEventSerializer $eventSerializer
    ) {}

    public function handle(IntegrationEventMessage $message): void
    {
        $event = $message->event;
        $data = $this->eventSerializer->serialize($event);
//        unset($data['bookingId']);
        $data['@event'] = $event::class;
        $this->historyStorage->register(
            new BookingId($event->bookingId),
            $this->exchangeEventToType($message->event),
            $data,
            $message->context
        );
    }

    private function exchangeEventToType(IntegrationEventInterface $event): EventGroupEnum
    {
        return match ($event::class) {
            StatusUpdated::class => EventGroupEnum::STATUS_UPDATED,
            RequestSent::class => EventGroupEnum::REQUEST_SENT,
            PriceChanged::class => EventGroupEnum::PRICE_CHANGED,

//            IntegrationEventMessages::HOTEL_BOOKING_ACCOMMODATION_ADDED,
//            IntegrationEventMessages::HOTEL_BOOKING_ACCOMMODATION_REPLACED,
//            IntegrationEventMessages::HOTEL_BOOKING_ACCOMMODATION_REMOVED,
//            IntegrationEventMessages::HOTEL_BOOKING_ACCOMMODATION_DETAILS_EDITED => BookingEventEnum::DETAILS_MODIFIED,
            default => EventGroupEnum::OTHER,
        };
    }
}
