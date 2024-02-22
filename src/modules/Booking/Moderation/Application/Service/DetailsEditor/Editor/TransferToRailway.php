<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Service\DetailsEditor\Editor;

use Module\Booking\Shared\Domain\Booking\Factory\Details\TransferToRailwayFactoryInterface;
use Module\Supplier\Moderation\Infrastructure\Models\Service as InfrastructureSupplierService;
use Sdk\Booking\Entity\Details\TransferToRailway as Entity;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\ServiceId;
use Sdk\Booking\ValueObject\ServiceInfo;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;

class TransferToRailway extends AbstractEditor implements EditorInterface
{
    public function __construct(
        DomainEventDispatcherInterface $eventDispatcher,
        private readonly TransferToRailwayFactoryInterface $detailsFactory,
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
            $detailsData['meetingTablet'] ?? null,
            $detailsData['date'] ?? null,
        );
    }
}
