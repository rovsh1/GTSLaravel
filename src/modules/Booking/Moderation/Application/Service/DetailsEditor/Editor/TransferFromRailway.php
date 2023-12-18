<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Service\DetailsEditor\Editor;

use Module\Booking\Shared\Domain\Booking\Factory\Details\TransferFromRailwayFactoryInterface;
use Module\Supplier\Moderation\Infrastructure\Models\Service as InfrastructureSupplierService;
use Sdk\Booking\Entity\Details\TransferFromRailway as Entity;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\ServiceId;
use Sdk\Booking\ValueObject\ServiceInfo;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;

class TransferFromRailway extends AbstractEditor implements EditorInterface
{
    public function __construct(
        DomainEventDispatcherInterface $eventDispatcher,
        private readonly TransferFromRailwayFactoryInterface $detailsFactory,
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
            (int)$supplierService->data['railwayStationId'],
            (int)$supplierService->data['cityId'],
            $detailsData['trainNumber'] ?? null,
            $detailsData['date'] ?? null,
            $detailsData['meetingTablet'] ?? null,
        );
    }
}
