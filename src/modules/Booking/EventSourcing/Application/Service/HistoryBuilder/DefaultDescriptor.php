<?php

namespace Module\Booking\EventSourcing\Application\Service\HistoryBuilder;

use Module\Booking\EventSourcing\Infrastructure\Model\BookingHistory;
use Sdk\Booking\IntegrationEvent\BookingCreated;
use Sdk\Booking\IntegrationEvent\PriceChanged;

class DefaultDescriptor extends AbstractDescriptor implements DescriptorInterface
{
    public function build(BookingHistory $history): DescriptionDto
    {
        return new DescriptionDto(
            $this->translateEvent($history->payload['@event'])
        );
    }

    private function translateEvent(string $event): string
    {
        return match ($event) {
            BookingCreated::class => 'Бронь создана',
            PriceChanged::class => 'Изменена стоимость',
//            IntegrationEventMessages::HOTEL_BOOKING_ACCOMMODATION_ADDED => 'Добавлено размещение',
//            IntegrationEventMessages::HOTEL_BOOKING_ACCOMMODATION_REMOVED => 'Размещение было удалено',
//            IntegrationEventMessages::HOTEL_BOOKING_ACCOMMODATION_REPLACED => 'Размещение было заменено',
//            IntegrationEventMessages::HOTEL_BOOKING_ACCOMMODATION_DETAILS_EDITED => 'Изменены детали размещения',
//            IntegrationEventMessages::HOTEL_BOOKING_GUEST_ADDED => 'Добавлен гость',
//            IntegrationEventMessages::HOTEL_BOOKING_GUEST_REMOVED => 'Гость удален',
            default => $event
        };
    }
}