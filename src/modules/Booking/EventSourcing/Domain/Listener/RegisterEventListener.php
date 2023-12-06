<?php

namespace Module\Booking\EventSourcing\Domain\Listener;

use Module\Booking\EventSourcing\Domain\Service\HistoryStorageInterface;
use Module\Booking\EventSourcing\Domain\ValueObject\EventGroupEnum;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Module\Contracts\Event\IntegrationEventListenerInterface;
use Sdk\Module\Contracts\Event\IntegrationEventMessage;
use Sdk\Shared\Event\IntegrationEventMessages;

class RegisterEventListener implements IntegrationEventListenerInterface
{
    public function __construct(
        private readonly HistoryStorageInterface $historyStorage,
    ) {}

    public function handle(IntegrationEventMessage $message): void
    {
        $data = $message->payload;
        unset($data['bookingId']);
        $data['@event'] = $message->event;
        $this->historyStorage->register(
            new BookingId($message->payload['bookingId']),
            $this->exchangeEventToType($message->event),
            $data,
            $message->context
        );
    }

    private function exchangeEventToType(string $event): EventGroupEnum
    {
        return match ($event) {
            IntegrationEventMessages::BOOKING_STATUS_UPDATED => EventGroupEnum::STATUS_UPDATED,
            IntegrationEventMessages::BOOKING_PRICE_CHANGED => EventGroupEnum::PRICE_CHANGED,
            IntegrationEventMessages::BOOKING_REQUEST_SENT => EventGroupEnum::REQUEST_SENT,
//            IntegrationEventMessages::HOTEL_BOOKING_ACCOMMODATION_ADDED,
//            IntegrationEventMessages::HOTEL_BOOKING_ACCOMMODATION_REPLACED,
//            IntegrationEventMessages::HOTEL_BOOKING_ACCOMMODATION_REMOVED,
//            IntegrationEventMessages::HOTEL_BOOKING_ACCOMMODATION_DETAILS_EDITED => BookingEventEnum::DETAILS_MODIFIED,
            default => EventGroupEnum::DETAILS_MODIFIED,
        };
    }
}
