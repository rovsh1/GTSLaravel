<?php

declare(strict_types=1);

namespace Module\Booking\Common\Application\Support\UseCase\Admin;

use Module\Booking\Airport\Application\Request\CreateBookingDto as CreateAirportBooking;
use Module\Booking\Common\Domain\Repository\BookingRepositoryInterface;
use Module\Booking\Hotel\Application\Request\CreateBookingDto as CreateHotelBookingDto;
use Module\Booking\Order\Application\Command\CreateOrder;
use Sdk\Module\Contracts\Bus\CommandBusInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

abstract class AbstractCreateBooking implements UseCaseInterface
{
    public function __construct(
        protected readonly CommandBusInterface $commandBus,
        protected readonly BookingRepositoryInterface $repository
    ) {}

    protected function getOrderIdFromRequest(CreateHotelBookingDto|CreateAirportBooking $request): int
    {
        $orderId = $request->orderId;
        if ($orderId === null) {
            $orderId = $this->commandBus->execute(
                new CreateOrder($request->clientId, $request->legalId, $request->currencyId)
            );
        }

        return $orderId;
    }
}
