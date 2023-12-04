<?php

namespace Module\Booking\Requesting\Domain\Booking\Listener;

use Module\Booking\Requesting\Domain\Booking\Service\ChangesRegistratorInterface;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Module\Contracts\Event\IntegrationEventListenerInterface;
use Sdk\Module\Contracts\Event\IntegrationEventMessage;

class RegisterChangesListener implements IntegrationEventListenerInterface
{
    public function __construct(
        private readonly ChangesRegistratorInterface $changesRegistrator,
    ) {}

    public function handle(IntegrationEventMessage $message): void
    {
//        dd($message);
        $data = $message->payload;
        unset($data['bookingId']);
        $data['@event'] = $message->event;
        $this->changesRegistrator->register(
            new BookingId($message->payload['bookingId']),
            $data,
            $message->context
        );
    }
}
