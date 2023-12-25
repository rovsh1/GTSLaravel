<?php

namespace Module\Booking\Requesting\Domain\Listener;

use Module\Booking\Requesting\Domain\Service\ChangesRegistratorInterface;
use Module\Booking\Requesting\Domain\Service\EventComparator\ComparatorFactory;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Sdk\Booking\Enum\StatusEnum;
use Sdk\Booking\IntegrationEvent\BookingEventInterface;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Module\Contracts\Event\IntegrationEventListenerInterface;
use Sdk\Module\Event\IntegrationEventMessage;

class RegisterChangesListener implements IntegrationEventListenerInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly ComparatorFactory $comparatorFactory,
        private readonly ChangesRegistratorInterface $changesRegistrator,
    ) {}

    public function handle(IntegrationEventMessage $message): void
    {
        $event = $message->event;
        asset($event instanceof BookingEventInterface);

        $booking = $this->bookingRepository->findOrFail(new BookingId($event->bookingId));
        if (!$this->needStoreChanges($booking->status()->value())) {
            return;
        }

        $comparator = $this->comparatorFactory->build($event);
        if (!$comparator) {
            return;
        }

        $changesDto = $comparator->get($event);

        $this->changesRegistrator->register(
            new BookingId($event->bookingId),
            $changesDto->field,
            $changesDto->before,
            $changesDto->after,
        );
    }

    private function needStoreChanges(StatusEnum $status): bool
    {
        return $status === StatusEnum::NOT_CONFIRMED;
    }
}
