<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Service\DetailsEditor\Editor;

use Module\Booking\Domain\Booking\Entity\CIPRoomInAirport as Entity;
use Module\Booking\Domain\Booking\Repository\Details\CIPRoomInAirportRepositoryInterface;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Booking\ValueObject\ServiceId;
use Module\Booking\Domain\Booking\ValueObject\ServiceInfo;
use Module\Booking\Domain\Shared\ValueObject\GuestIdCollection;
use Module\Booking\Moderation\Application\Service\DetailsEditor\EditorInterface;
use Module\Supplier\Infrastructure\Models\Service as InfrastructureSupplierService;

class CIPRoomInAirport extends AbstractEditor implements EditorInterface
{
    public function __construct(
        private readonly CIPRoomInAirportRepositoryInterface $detailsRepository,
    ) {}

    public function create(BookingId $bookingId, ServiceId $serviceId, array $detailsData): Entity
    {
        $supplierService = InfrastructureSupplierService::find($serviceId->value());

        $serviceInfo = new ServiceInfo($serviceId->value(), $supplierService->title, $supplierService->supplier_id);

        return $this->detailsRepository->create(
            $bookingId,
            $serviceInfo,
            (int)$supplierService->data['airportId'],
            $detailsData['flightNumber'] ?? null,
            $detailsData['serviceDate'] ?? null,
            new GuestIdCollection([])
        );
    }

    public function update(BookingId $bookingId, array $detailsData): void
    {
        $details = $this->detailsRepository->find($bookingId);
        foreach ($detailsData as $field => $value) {
            $this->setField($details, $field, $value);
        }
        $this->detailsRepository->store($details);
    }
}
