<?php

namespace Module\Booking\Shared\Domain\Booking\Service\IntegrationEventMapper\Transfer;

use Module\Booking\Shared\Domain\Booking\Adapter\SupplierAdapterInterface;
use Module\Booking\Shared\Domain\Booking\Service\IntegrationEventMapper\MapperInterface;
use Sdk\Booking\Event\TransferBooking\CarBidDetailsEdited;
use Sdk\Booking\IntegrationEvent\TransferBooking\CarBidModified;
use Sdk\Module\Contracts\Event\DomainEventInterface;

class CarBidModifiedMapper implements MapperInterface
{
    public function __construct(
        private readonly SupplierAdapterInterface $supplierAdapter,
    ) {}

    public function map(DomainEventInterface $event): array
    {
        assert($event instanceof CarBidDetailsEdited);

        $car = $this->supplierAdapter->findCar($event->carBid->carId()->value());

        return [
            new CarBidModified(
                $event->bookingId()->value(),
                $event->carBid->id()->value(),
                $event->carBid->carId()->value(),
                $car?->mark . ' ' . $car?->model,
                $event->detailsBefore->serialize(),
                $event->carBid->details()->serialize(),
            )
        ];
    }
}
