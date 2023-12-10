<?php

namespace Module\Booking\Requesting\Domain\Listener;

use Module\Booking\Requesting\Domain\Service\ChangesRegistratorInterface;
use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Sdk\Booking\Enum\StatusEnum;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Module\Contracts\Event\IntegrationEventListenerInterface;
use Sdk\Module\Event\IntegrationEventMessage;

class RegisterChangesListener implements IntegrationEventListenerInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly ChangesRegistratorInterface $changesRegistrator,
    ) {}

    public function handle(IntegrationEventMessage $message): void
    {
        $booking = $this->bookingRepository->findOrFail(new BookingId($message->payload['bookingId']));
        if (!$this->isActualStatus($booking)) {
            return;
        }


        $data = $message->payload;
        unset($data['bookingId']);
        $data['@event'] = $message->event;
        $this->changesRegistrator->register(
            new BookingId($message->payload['bookingId']),
            $data,
            $message->context
        );
    }

    private function isActualStatus(Booking $booking): bool
    {
        return $booking->status() === StatusEnum::NOT_CONFIRMED;
    }
}
