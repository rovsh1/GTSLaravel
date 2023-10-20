<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\ServiceBooking\Service\DetailsEditor\Editor;

use Module\Booking\Application\Admin\ServiceBooking\Service\DetailsEditor\Editor\AbstractEditor;
use Module\Booking\Application\Admin\ServiceBooking\Service\DetailsEditor\EditorInterface;
use Module\Booking\Domain\Booking\Entity\ServiceDetailsInterface;
use Module\Booking\Domain\Booking\Repository\Details\CarRentWithDriverRepositoryInterface;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Booking\ValueObject\CarBidCollection;
use Module\Booking\Domain\Booking\ValueObject\ServiceId;
use Module\Booking\Domain\Booking\ValueObject\ServiceInfo;
use Module\Supplier\Infrastructure\Models\Service as InfrastructureSupplierService;

class CarRentWithDriver extends AbstractEditor implements EditorInterface
{
    public function __construct(
        private readonly CarRentWithDriverRepositoryInterface $detailsRepository
    ) {}

    public function create(BookingId $bookingId, ServiceId $serviceId, array $detailsData): ServiceDetailsInterface
    {
        $supplierService = InfrastructureSupplierService::find($serviceId->value());

        $serviceInfo = new ServiceInfo($serviceId->value(), $supplierService->title, $supplierService->supplier_id);

        $hoursLimit = $supplierService->data['hoursLimit'] ?? null;

        return $this->detailsRepository->create(
            $bookingId,
            $serviceInfo,
            (int)$supplierService->data['cityId'],
            $hoursLimit ? (int)$hoursLimit : null,
            new CarBidCollection([]),
            $detailsData['date'] ?? null,
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
