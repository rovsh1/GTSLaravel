<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Application\UseCase\Admin;

use Module\Booking\Hotel\Application\Command;
use Module\Booking\Hotel\Application\Request\CreateBookingDto;
use Sdk\Module\Contracts\Bus\CommandBusInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class CreateBooking implements UseCaseInterface
{
    public function __construct(
        private readonly CommandBusInterface $commandBus
    ) {}

    public function execute(CreateBookingDto $request): int
    {
        return $this->commandBus->execute(
            new Command\Admin\CreateBooking(
                cityId: $request->cityId,
                clientId: $request->clientId,
                hotelId: $request->hotelId,
                period: $request->period,
                creatorId: $request->creatorId,
                orderId: $request->orderId,
                note: $request->note,
            )
        );
    }
}
