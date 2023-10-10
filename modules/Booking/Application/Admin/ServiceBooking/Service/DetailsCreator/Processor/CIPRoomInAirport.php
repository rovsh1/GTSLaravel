<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\ServiceBooking\Service\DetailsCreator\Processor;

use Module\Booking\Application\Admin\ServiceBooking\Service\DetailsCreator\ProcessorInterface;
use Module\Booking\Domain\ServiceBooking\Entity\CIPRoomInAirport as Entity;
use Module\Booking\Domain\ServiceBooking\Repository\Details\CIPRoomInAirportRepositoryInterface;
use Module\Booking\Domain\ServiceBooking\ValueObject\BookingId;
use Module\Booking\Domain\ServiceBooking\ValueObject\ServiceId;
use Module\Booking\Domain\ServiceBooking\ValueObject\ServiceInfo;
use Module\Booking\Domain\Shared\ValueObject\GuestIdCollection;
use Module\Shared\Enum\ServiceTypeEnum;
use Module\Supplier\Infrastructure\Models\Service as InfrastructureSupplierService;

class CIPRoomInAirport implements ProcessorInterface
{
    public function __construct(
        private readonly CIPRoomInAirportRepositoryInterface $detailsRepository,
    ) {}

    public function process(BookingId $bookingId, ServiceId $serviceId, array $detailsData): Entity
    {
        $supplierService = InfrastructureSupplierService::find($serviceId->value());

        $serviceInfo = new ServiceInfo(
            $serviceId,
            $supplierService->title,
            ServiceTypeEnum::CIP_IN_AIRPORT
        );

        return $this->detailsRepository->create(
            $bookingId,
            $serviceInfo,
            $supplierService->data['airportId'],
        $detailsData['flightNumber'] ?? null,
            $detailsData['serviceDate'] ?? null,
            new GuestIdCollection([])
        );
    }
}
