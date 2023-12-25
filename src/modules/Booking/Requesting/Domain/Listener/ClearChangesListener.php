<?php

namespace Module\Booking\Requesting\Domain\Listener;

use Module\Booking\Requesting\Domain\Service\ChangesRegistratorInterface;
use Sdk\Booking\Enum\StatusEnum;
use Sdk\Booking\IntegrationEvent\StatusUpdated;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Module\Contracts\Event\IntegrationEventListenerInterface;
use Sdk\Module\Event\IntegrationEventMessage;

class ClearChangesListener implements IntegrationEventListenerInterface
{
    public function __construct(
        private readonly ChangesRegistratorInterface $changesRegistrator
    ) {}

    public function handle(IntegrationEventMessage $message): void
    {
        assert($message->event instanceof StatusUpdated);

        if (!$this->isClearNeeded($message->event->status)) {
            return;
        }

        $this->changesRegistrator->clear(new BookingId($message->event->bookingId));
    }

    private function isClearNeeded(StatusEnum $status): bool
    {
        return false;
    }
}