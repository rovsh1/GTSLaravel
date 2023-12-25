<?php

namespace Module\Booking\EventSourcing\Domain\Listener;

use Module\Booking\EventSourcing\Domain\Service\EventDescriptor\DescriptorFactory;
use Module\Booking\EventSourcing\Domain\Service\HistoryStorageInterface;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Module\Contracts\Event\IntegrationEventListenerInterface;
use Sdk\Module\Event\IntegrationEventMessage;

class RegisterEventListener implements IntegrationEventListenerInterface
{
    public function __construct(
        private readonly HistoryStorageInterface $historyStorage,
        private readonly DescriptorFactory $descriptorFactory,
    ) {}

    public function handle(IntegrationEventMessage $message): void
    {
        $event = $message->event;
        $descriptor = $this->descriptorFactory->build($event);
        $descriptionDto = $descriptor->build($event);
        $this->historyStorage->register(
            new BookingId($event->bookingId),
            $descriptionDto->group,
            $descriptionDto->field,
            $descriptionDto->description,
            $descriptionDto->before,
            $descriptionDto->after,
            $message->context
        );
    }
}
