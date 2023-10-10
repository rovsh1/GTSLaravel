<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\ServiceBooking\Service\DetailsCreator\Processor;

use Module\Booking\Application\Admin\ServiceBooking\Service\DetailsCreator\ProcessorInterface;
use Module\Booking\Domain\ServiceBooking\Entity\CIPRoomInAirport as Entity;
use Module\Booking\Domain\ServiceBooking\Repository\DetailsRepositoryInterface;
use Module\Booking\Domain\ServiceBooking\ValueObject\BookingId;
use Module\Booking\Domain\Shared\ValueObject\GuestIdCollection;
use Module\Supplier\Application\Response\ServiceDto;

class CIPRoomInAirport implements ProcessorInterface
{
    public function __construct(
        private readonly DetailsRepositoryInterface $detailsRepository,
    ) {}

    public function process(BookingId $bookingId, ServiceDto $service, array $detailsData): Entity
    {
        return $this->detailsRepository->createCIPRoomInAirport(
            $bookingId,
            $service->title,
            $service->details->airportId,
            $detailsData['flightNumber'] ?? null,
            $detailsData['serviceDate'] ?? null,
            new GuestIdCollection([])
        );
    }
}
