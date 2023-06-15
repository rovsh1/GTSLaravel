<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Application\UseCase\Admin;

use Module\Booking\Airport\Application\Request\CreateBookingDto;
use Module\Booking\Airport\Domain\Repository\AirportRepositoryInterface;
use Module\Booking\Airport\Domain\Repository\BookingRepositoryInterface;
use Module\Booking\Airport\Domain\Repository\ServiceRepositoryInterface;
use Module\Booking\Common\Application\Support\UseCase\Admin\AbstractCreateBooking;
use Sdk\Module\Contracts\Bus\CommandBusInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class CreateBooking extends AbstractCreateBooking
{
    public function __construct(
        CommandBusInterface $commandBus,
        BookingRepositoryInterface $repository,
        private readonly AirportRepositoryInterface $airportRepository,
        private readonly ServiceRepositoryInterface $serviceRepository,
    ) {
        parent::__construct($commandBus, $repository);
    }

    public function execute(CreateBookingDto $request): int
    {
        $orderId = $this->getOrderIdFromRequest($request);
        $airport = $this->airportRepository->get($request->airportId);
        if ($airport === null) {
            throw new EntityNotFoundException('Airport not found');
        }
        $service = $this->serviceRepository->get($request->serviceId);
        if ($service === null) {
            throw new EntityNotFoundException('Service not found');
        }
        $booking = $this->repository->create(
            $orderId,
            $request->creatorId,
            $service,
            $airport,
            $request->date,
            $request->note,
        );

        return $booking->id()->value();
    }
}
