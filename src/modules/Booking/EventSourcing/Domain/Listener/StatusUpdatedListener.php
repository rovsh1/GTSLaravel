<?php

namespace Module\Booking\EventSourcing\Domain\Listener;

use Module\Booking\EventSourcing\Domain\Repository\BookingLogRepositoryInterface;
use Module\Booking\EventSourcing\Domain\ValueObject\BookingEventEnum;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Sdk\Module\Contracts\Event\IntegrationEventListenerInterface;
use Sdk\Module\Contracts\Event\IntegrationEventMessage;

class StatusUpdatedListener implements IntegrationEventListenerInterface
{
    public function __construct(
        private readonly BookingLogRepositoryInterface $changesLogRepository
    ) {
    }

    public function handle(IntegrationEventMessage $message): void
    {
        $this->changesLogRepository->register(
            new BookingId($message->payload['bookingId']),
            BookingEventEnum::STATUS_UPDATED,
            ['status' => $message->payload['status']],
            $message->context
        );
    }
}
