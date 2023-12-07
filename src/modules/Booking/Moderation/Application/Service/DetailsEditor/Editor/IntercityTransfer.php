<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Service\DetailsEditor\Editor;

use Module\Booking\Shared\Domain\Booking\Factory\Details\IntercityTransferFactoryInterface;
use Module\Supplier\Moderation\Infrastructure\Models\Service as InfrastructureSupplierService;
use Sdk\Booking\Contracts\Entity\DetailsInterface;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\ServiceId;
use Sdk\Booking\ValueObject\ServiceInfo;

class IntercityTransfer extends AbstractEditor implements EditorInterface
{
    public function __construct(
        private readonly IntercityTransferFactoryInterface $detailsFactory
    ) {}

    public function create(BookingId $bookingId, ServiceId $serviceId, array $detailsData): DetailsInterface
    {
        $supplierService = InfrastructureSupplierService::find($serviceId->value());

        $serviceInfo = new ServiceInfo($serviceId->value(), $supplierService->title, $supplierService->supplier_id);

        return $this->detailsFactory->create(
            $bookingId,
            $serviceInfo,
            (int)$supplierService->data['fromCityId'],
            (int)$supplierService->data['toCityId'],
            (bool)$supplierService->data['returnTripIncluded'],
            $detailsData['date'] ?? null,
        );
    }
}
