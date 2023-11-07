<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Service\DetailsEditor\Editor;

use Module\Booking\Moderation\Application\Service\DetailsEditor\EditorInterface;
use Module\Booking\Shared\Domain\Booking\Entity\TransferToRailway as Entity;
use Module\Booking\Shared\Domain\Booking\Repository\Details\TransferToRailwayRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\CarBidCollection;
use Module\Booking\Shared\Domain\Booking\ValueObject\ServiceId;
use Module\Booking\Shared\Domain\Booking\ValueObject\ServiceInfo;
use Module\Supplier\Infrastructure\Models\Service as InfrastructureSupplierService;

class TransferToRailway extends AbstractEditor implements EditorInterface
{
    public function __construct(
        private readonly TransferToRailwayRepositoryInterface $detailsRepository,
    ) {}

    public function create(BookingId $bookingId, ServiceId $serviceId, array $detailsData): Entity
    {
        $supplierService = InfrastructureSupplierService::find($serviceId->value());

        $serviceInfo = new ServiceInfo($serviceId->value(), $supplierService->title, $supplierService->supplier_id);

        return $this->detailsRepository->create(
            $bookingId,
            $serviceInfo,
            (int)$supplierService->data['railwayStationId'],
            (int)$supplierService->data['cityId'],
            new CarBidCollection([]),
            $detailsData['trainNumber'] ?? null,
            $detailsData['meetingTablet'] ?? null,
            $detailsData['departureDate'] ?? null,
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
