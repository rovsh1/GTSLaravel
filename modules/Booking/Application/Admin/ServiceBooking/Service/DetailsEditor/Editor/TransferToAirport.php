<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\ServiceBooking\Service\DetailsEditor\Editor;

use Module\Booking\Application\Admin\ServiceBooking\Service\DetailsEditor\EditorInterface;
use Module\Booking\Domain\Booking\Entity\TransferToAirport as Entity;
use Module\Booking\Domain\Booking\Repository\Details\TransferToAirportRepositoryInterface;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Booking\ValueObject\ServiceId;
use Module\Booking\Domain\Booking\ValueObject\ServiceInfo;
use Module\Supplier\Infrastructure\Models\Service as InfrastructureSupplierService;

class TransferToAirport implements EditorInterface
{
    public function __construct(
        private readonly TransferToAirportRepositoryInterface $detailsRepository,
    ) {}

    public function create(BookingId $bookingId, ServiceId $serviceId, array $detailsData): Entity
    {
        $supplierService = InfrastructureSupplierService::find($serviceId->value());

        $serviceInfo = new ServiceInfo($serviceId, $supplierService->title);

        return $this->detailsRepository->create(
            $bookingId,
            $serviceInfo,
            $supplierService->data['airportId'],
            $detailsData['flightNumber'] ?? null,
            $detailsData['departureDate'] ?? null,
        );
    }

    public function update(BookingId $bookingId, array $detailsData): void
    {
        $details = $this->detailsRepository->find($bookingId);
        //@todo проверка полей на изменение и заполнение
        $this->detailsRepository->store($details);
    }
}
