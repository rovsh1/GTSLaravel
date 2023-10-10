<?php

declare(strict_types=1);

namespace Module\Booking\Domain\ServiceBooking\Service\DetailsCreator\Processor;

use Module\Booking\Domain\ServiceBooking\Entity\TransferFromAirport as Entity;
use Module\Booking\Domain\ServiceBooking\Repository\DetailsRepositoryInterface;
use Module\Booking\Domain\ServiceBooking\Service\DetailsCreator\ProcessorInterface;
use Module\Booking\Domain\ServiceBooking\ValueObject\BookingId;
use Module\Supplier\Application\Response\ServiceDto;

class TransferFromAirport implements ProcessorInterface
{
    public function __construct(
        private readonly DetailsRepositoryInterface $detailsRepository,
    ) {}

    public function process(BookingId $bookingId, ServiceDto $service, array $detailsData): Entity
    {
        return $this->detailsRepository->createTransferFromAirport(
            $bookingId,
            $service->title,
            $service->details->airportId,
            $detailsData['flightNumber'] ?? null,
            $detailsData['arrivalDate'] ?? null,
            $detailsData['meetingTablet'] ?? null,
        );
    }
}
