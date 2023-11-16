<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Service\DetailsEditor\Editor;

use Module\Booking\Shared\Domain\Booking\Entity\TransferFromAirport as Entity;
use Module\Booking\Shared\Domain\Booking\Factory\Details\TransferFromAirportFactoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\CarBidCollection;
use Module\Booking\Shared\Domain\Booking\ValueObject\ServiceId;
use Module\Booking\Shared\Domain\Booking\ValueObject\ServiceInfo;
use Module\Supplier\Moderation\Infrastructure\Models\Service as InfrastructureSupplierService;

class TransferFromAirport extends AbstractEditor implements EditorInterface
{
    public function __construct(
        private readonly TransferFromAirportFactoryInterface $detailsFactory,
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
            new CarBidCollection([]),
            $detailsData['flightNumber'] ?? null,
            $detailsData['arrivalDate'] ?? null,
            $detailsData['meetingTablet'] ?? null,
        );
    }
}
