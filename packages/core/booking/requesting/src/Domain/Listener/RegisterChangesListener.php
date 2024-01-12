<?php

namespace Pkg\Booking\Requesting\Domain\Listener;

use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Pkg\Booking\Requesting\Domain\Service\ChangesRegistrar\RegistrarFactory;
use Sdk\Booking\Enum\StatusEnum;
use Sdk\Booking\IntegrationEvent\BookingEventInterface;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Shared\Contracts\Event\IntegrationEventListenerInterface;
use Sdk\Shared\Event\IntegrationEventMessage;

class RegisterChangesListener implements IntegrationEventListenerInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly RegistrarFactory $registrarFactory,
    ) {}

    public function handle(IntegrationEventMessage $message): void
    {
        $event = $message->event;
        asset($event instanceof BookingEventInterface);

        $booking = $this->bookingRepository->findOrFail(new BookingId($event->bookingId));
        if (!$this->needStoreChanges($booking->status()->value())) {
            return;
        }

        $registrar = $this->registrarFactory->build($event);
        if (!$registrar) {
            return;
        }

        $registrar->register($event);
    }

    private function needStoreChanges(StatusEnum $status): bool
    {
        return in_array($status, [
            StatusEnum::NOT_CONFIRMED,
            StatusEnum::WAITING_PROCESSING,
        ]);
    }
}
