<?php

namespace Pkg\Booking\EventSourcing\Domain\Service\EventDescriptor;

use Sdk\Booking\IntegrationEvent\BookingCreated;
use Sdk\Shared\Contracts\Event\IntegrationEventInterface;

class DefaultDescriptor extends AbstractDescriptor implements DescriptorInterface
{
    public function build(IntegrationEventInterface $event): DescriptionDto
    {
        return new DescriptionDto(
            group: null,
            field: null,
            description: $this->translateEvent($event::class),
            before: null,
            after: null
        );
    }

    private function translateEvent(string $event): string
    {
        return match ($event) {
            BookingCreated::class => 'Бронь создана',
//            PriceChanged::class => 'Изменена стоимость',
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