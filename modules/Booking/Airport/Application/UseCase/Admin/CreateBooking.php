<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Application\UseCase\Admin;

use Module\Booking\Airport\Application\Request\CreateBookingDto;
use Module\Booking\Airport\Infrastructure\Repository\BookingRepository;
use Module\Booking\Common\Application\Support\UseCase\Admin\AbstractCreateBooking;
use Sdk\Module\Contracts\Bus\CommandBusInterface;

class CreateBooking extends AbstractCreateBooking
{
    public function __construct(CommandBusInterface $commandBus, BookingRepository $repository)
    {
        parent::__construct($commandBus, $repository);
    }

    public function execute(CreateBookingDto $request): int
    {
        $orderId = $this->getOrderIdFromRequest($request);
        $booking = $this->repository->create(
            $orderId,
            $request->creatorId,
            $request->airportId,
            $request->date,
            $request->note,
        );

        return $booking->id()->value();
    }
}
