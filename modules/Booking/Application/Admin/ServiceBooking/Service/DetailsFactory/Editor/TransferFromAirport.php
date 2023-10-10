<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\ServiceBooking\Service\DetailsFactory\Editor;

use Module\Booking\Application\Admin\ServiceBooking\Service\DetailsFactory\EditorInterface;
use Module\Booking\Domain\ServiceBooking\Entity\TransferFromAirport as Entity;
use Module\Booking\Domain\ServiceBooking\Repository\Details\TransferFromAirportRepositoryInterface;
use Module\Booking\Domain\ServiceBooking\ValueObject\BookingId;
use Module\Booking\Domain\ServiceBooking\ValueObject\ServiceId;
use Module\Booking\Domain\ServiceBooking\ValueObject\ServiceInfo;
use Module\Supplier\Infrastructure\Models\Service as InfrastructureSupplierService;

class TransferFromAirport implements EditorInterface
{
    public function __construct(
        private readonly TransferFromAirportRepositoryInterface $detailsRepository,
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
            $detailsData['arrivalDate'] ?? null,
            $detailsData['meetingTablet'] ?? null,
        );
    }

    public function update(BookingId $bookingId, array $detailsData): void
    {
        $details = $this->detailsRepository->find($bookingId);
        //@todo проверка полей на изменение и заполнение
        $this->detailsRepository->store($details);
    }
}
