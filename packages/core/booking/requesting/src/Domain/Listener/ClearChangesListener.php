<?php

namespace Pkg\Booking\Requesting\Domain\Listener;

use Pkg\Booking\Requesting\Domain\Service\ChangesStorageInterface;
use Sdk\Booking\Enum\StatusEnum;
use Sdk\Booking\IntegrationEvent\StatusUpdated;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Shared\Contracts\Event\IntegrationEventListenerInterface;
use Sdk\Shared\Event\IntegrationEventMessage;

class ClearChangesListener implements IntegrationEventListenerInterface
{
    public function __construct(
        private readonly ChangesStorageInterface $changesRegistrator
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
        return $status === StatusEnum::CONFIRMED;
    }
}
