<?php

namespace Module\Booking\EventSourcing\Application\Service\HistoryBuilder;

use Module\Booking\EventSourcing\Application\Dto\EventDto;
use Module\Booking\EventSourcing\Infrastructure\Model\BookingHistory;
use Sdk\Shared\Event\IntegrationEventMessages;

class DefaultDtoFactory extends AbstractDtoFactory implements DtoFactoryInterface
{
    public function build(BookingHistory $history): EventDto
    {
        return $this->wrap(
            $history,
            $this->translateEvent($history->payload['@event'])
        );
    }

    private function translateEvent(string $event): string
    {
        return match ($event) {
            IntegrationEventMessages::BOOKING_CREATED => 'Бронь создана',
            IntegrationEventMessages::BOOKING_PRICE_CHANGED => 'Изменена стоимость',
            IntegrationEventMessages::HOTEL_BOOKING_ACCOMMODATION_ADDED => 'Добавлено размещение',
            IntegrationEventMessages::HOTEL_BOOKING_ACCOMMODATION_REMOVED => 'Размещение было удалено',
            IntegrationEventMessages::HOTEL_BOOKING_ACCOMMODATION_REPLACED => 'Размещение было заменено',
            IntegrationEventMessages::HOTEL_BOOKING_ACCOMMODATION_DETAILS_EDITED => 'Изменены детали размещения',
            IntegrationEventMessages::HOTEL_BOOKING_GUEST_ADDED => 'Добавлен гость',
            IntegrationEventMessages::HOTEL_BOOKING_GUEST_REMOVED => 'Гость удален',
            default => $event
        };
    }
}