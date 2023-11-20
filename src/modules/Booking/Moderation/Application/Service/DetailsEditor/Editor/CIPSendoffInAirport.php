<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Service\DetailsEditor\Editor;

use Module\Booking\Shared\Domain\Booking\Entity\CIPSendoffInAirport as Entity;
use Module\Booking\Shared\Domain\Booking\Factory\Details\CIPSendoffInAirportFactoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\ServiceId;
use Module\Booking\Shared\Domain\Booking\ValueObject\ServiceInfo;
use Module\Booking\Shared\Domain\Shared\ValueObject\GuestIdCollection;
use Module\Supplier\Moderation\Infrastructure\Models\Service as InfrastructureSupplierService;

class CIPSendoffInAirport extends AbstractEditor implements EditorInterface
{
    public function __construct(
        private readonly CIPSendoffInAirportFactoryInterface $detailsFactory,
    ) {
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
            $detailsData['departureDate'] ?? null,
            new GuestIdCollection([])
        );
    }
}
