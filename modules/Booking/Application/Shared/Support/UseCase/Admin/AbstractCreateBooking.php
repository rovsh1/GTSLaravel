<?php

declare(strict_types=1);

namespace Module\Booking\Application\Shared\Support\UseCase\Admin;

use Module\Booking\Application\TransferBooking\Request\CreateBookingDto as CreateTransferBooking;
use Module\Booking\Application\AirportBooking\Request\CreateBookingDto as CreateAirportBooking;
use Module\Booking\Application\HotelBooking\Request\CreateBookingDto as CreateHotelBookingDto;
use Module\Booking\Application\Order\Command\CreateOrder;
use Module\Booking\Domain\Shared\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\Shared\ValueObject\OrderId;
use Sdk\Module\Contracts\Bus\CommandBusInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

abstract class AbstractCreateBooking implements UseCaseInterface
{
    public function __construct(
        protected readonly CommandBusInterface $commandBus,
        protected readonly BookingRepositoryInterface $repository
    ) {}

    protected function getOrderIdFromRequest(CreateHotelBookingDto|CreateAirportBooking|CreateTransferBooking $request): OrderId
    {
        $orderId = $request->orderId;
        if ($orderId === null) {
            $orderId = $this->commandBus->execute(
                new CreateOrder($request->clientId, $request->legalId, $request->currencyId)
            );
        }

        return new OrderId($orderId);
    }
}
