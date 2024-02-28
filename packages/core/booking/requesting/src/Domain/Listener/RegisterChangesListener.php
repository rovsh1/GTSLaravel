<?php

namespace Pkg\Booking\Requesting\Domain\Listener;

use Illuminate\Support\Facades\Log;
use Module\Booking\Shared\Domain\Booking\DbContext\BookingDbContextInterface;
use Pkg\Booking\Requesting\Domain\Service\ChangesRegistrar\RegistrarFactory;
use Sdk\Booking\Enum\StatusEnum;
use Sdk\Booking\IntegrationEvent\BookingEventInterface;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Shared\Contracts\Event\IntegrationEventListenerInterface;
use Sdk\Shared\Event\IntegrationEventMessage;

class RegisterChangesListener implements IntegrationEventListenerInterface
{
    public function __construct(
        private readonly BookingDbContextInterface $bookingRepository,
        private readonly RegistrarFactory $registrarFactory,
    ) {}

    public function handle(IntegrationEventMessage $message): void
    {
        Log::debug('RegisterChangesListener::handle start', ['message' => $message]);
        $event = $message->event;
        assert($event instanceof BookingEventInterface);

        Log::debug('RegisterChangesListener::handle event', ['event' => $event]);
        $booking = $this->bookingRepository->findOrFail(new BookingId($event->bookingId));
        if (!$this->needStoreChanges($booking->status()->value())) {
            Log::debug('RegisterChangesListener::handle dont need store', ['status' => $booking->status()->value()]);

            return;
        }

        Log::debug('RegisterChangesListener::handle need store');
        $registrar = $this->registrarFactory->build($event);
        if (!$registrar) {
            Log::debug('RegisterChangesListener::handle dont find registrar');

            return;
        }

        Log::debug('RegisterChangesListener::handle register');
        $registrar->register($event);
        Log::debug('RegisterChangesListener::handle end');
    }

    private function needStoreChanges(StatusEnum $status): bool
    {
        return in_array($status, [
            StatusEnum::NOT_CONFIRMED,
            StatusEnum::WAITING_PROCESSING,
        ]);
    }
}
