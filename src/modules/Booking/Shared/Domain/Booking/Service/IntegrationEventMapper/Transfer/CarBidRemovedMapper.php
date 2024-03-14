<?php

namespace Module\Booking\Shared\Domain\Booking\Service\IntegrationEventMapper\Transfer;

use Module\Booking\Shared\Domain\Booking\Adapter\SupplierAdapterInterface;
use Module\Booking\Shared\Domain\Booking\Service\IntegrationEventMapper\MapperInterface;
use Sdk\Booking\Event\TransferBooking\CarBidRemoved;
use Sdk\Module\Contracts\Event\DomainEventInterface;

class CarBidRemovedMapper implements MapperInterface
{
    public function __construct(
        private readonly SupplierAdapterInterface $supplierAdapter,
    ) {}

    public function map(DomainEventInterface $event): array
    {
        assert($event instanceof CarBidRemoved);

        $car = $this->supplierAdapter->findCar($event->carBid->carId()->value());

        return [
            new \Sdk\Booking\IntegrationEvent\TransferBooking\CarBidRemoved(
                $event->bookingId()->value(),
                $event->carBid->id()->value(),
                $event->carBid->carId()->value(),
                $car?->mark . ' ' . $car?->model,
            )
        ];
    }
}
