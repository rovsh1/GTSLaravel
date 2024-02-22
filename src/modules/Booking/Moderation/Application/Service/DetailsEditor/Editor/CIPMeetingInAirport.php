<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Service\DetailsEditor\Editor;

use Module\Booking\Shared\Domain\Booking\Factory\Details\CIPMeetingInAirportFactoryInterface;
use Module\Supplier\Moderation\Infrastructure\Models\Service as InfrastructureSupplierService;
use Sdk\Booking\Entity\Details\CIPMeetingInAirport as Entity;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\GuestIdCollection;
use Sdk\Booking\ValueObject\ServiceId;
use Sdk\Booking\ValueObject\ServiceInfo;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;

class CIPMeetingInAirport extends AbstractEditor implements EditorInterface
{
    public function __construct(
        DomainEventDispatcherInterface $eventDispatcher,
        private readonly CIPMeetingInAirportFactoryInterface $detailsFactory,
    ) {
        parent::__construct($eventDispatcher);
    }

    public function create(BookingId $bookingId, ServiceId $serviceId, array $detailsData): Entity
    {
        $supplierService = InfrastructureSupplierService::find($serviceId->value());

        $serviceInfo = new ServiceInfo($serviceId->value(), $supplierService->title, $supplierService->supplier_id);

        return $this->detailsFactory->create(
            $bookingId,
            $serviceInfo,
            (int)$supplierService->data['airportId'],
            $detailsData['flightNumber'] ?? null,
            $detailsData['date'] ?? null,
            new GuestIdCollection([])
        );
    }
}
