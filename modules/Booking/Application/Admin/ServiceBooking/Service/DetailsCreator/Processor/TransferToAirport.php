<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\ServiceBooking\Service\DetailsCreator\Processor;

use Module\Booking\Application\Admin\ServiceBooking\Service\DetailsCreator\ProcessorInterface;
use Module\Booking\Domain\ServiceBooking\Entity\TransferToAirport as Entity;
use Module\Booking\Domain\ServiceBooking\Repository\DetailsRepositoryInterface;
use Module\Booking\Domain\ServiceBooking\ValueObject\BookingId;
use Module\Supplier\Application\Response\ServiceDto;

class TransferToAirport implements ProcessorInterface
{
    public function __construct(
        private readonly DetailsRepositoryInterface $detailsRepository,
    ) {}

    public function process(BookingId $bookingId, ServiceDto $service, array $detailsData): Entity
    {
        return $this->detailsRepository->createTransferToAirport(
            $bookingId,
            $service->title,
            1,//@todo убрать hack
            $detailsData['flightNumber'] ?? null,
            $detailsData['departureDate'] ?? null,
        );
    }
}
