<?php

namespace Module\Booking\Shared\Domain\Booking\Service\IntegrationEventMapper\Transfer;

use Module\Booking\Shared\Domain\Booking\Adapter\SupplierAdapterInterface;
use Module\Booking\Shared\Domain\Booking\Service\IntegrationEventMapper\MapperInterface;
use Sdk\Booking\Event\TransferBooking\CarBidReplaced;
use Sdk\Booking\IntegrationEvent\TransferBooking\CarBidReplaced as IntegrationEvent;
use Sdk\Module\Contracts\Event\DomainEventInterface;

class CarBidReplacedMapper implements MapperInterface
{
    public function __construct(
        private readonly SupplierAdapterInterface $supplierAdapter,
    ) {}

    public function map(DomainEventInterface $event): array
    {
        assert($event instanceof CarBidReplaced);

        $currentCar = $this->supplierAdapter->findCar($event->carBidAfter->carId()->value());
        $oldCar = $this->supplierAdapter->findCar($event->carBidBefore->carId()->value());

        return [
            new IntegrationEvent(
                $event->bookingId()->value(),
                $event->carBidAfter->id()->value(),
                $event->carBidAfter->carId()->value(),
                $currentCar?->mark . ' ' . $currentCar?->model,
                $oldCar?->mark . ' ' . $oldCar?->model,
            )
        ];
    }
}
