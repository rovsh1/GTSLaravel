<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Service\DetailsEditor\Editor;

use Module\Booking\Shared\Domain\Booking\Entity\DetailsInterface;
use Module\Booking\Shared\Domain\Booking\Factory\Details\CarRentWithDriverFactoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\CarBidCollection;
use Module\Booking\Shared\Domain\Booking\ValueObject\ServiceId;
use Module\Booking\Shared\Domain\Booking\ValueObject\ServiceInfo;
use Module\Supplier\Moderation\Infrastructure\Models\Service as InfrastructureSupplierService;

class CarRentWithDriver extends AbstractEditor implements EditorInterface
{
    public function __construct(
        private readonly CarRentWithDriverFactoryInterface $detailsFactory
    ) {
    }

    public function create(BookingId $bookingId, ServiceId $serviceId, array $detailsData): DetailsInterface
    {
        $supplierService = InfrastructureSupplierService::find($serviceId->value());

        $serviceInfo = new ServiceInfo($serviceId->value(), $supplierService->title, $supplierService->supplier_id);

        return $this->detailsFactory->create(
            $bookingId,
            $serviceInfo,
            (int)$supplierService->data['cityId'],
            new CarBidCollection([]),
            null,
        );
    }
}
