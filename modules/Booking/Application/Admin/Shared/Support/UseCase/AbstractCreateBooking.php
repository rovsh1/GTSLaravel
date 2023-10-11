<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\Shared\Support\UseCase;

use Module\Booking\Application\Admin\Order\Command\CreateOrder;
use Module\Booking\Domain\Order\ValueObject\OrderId;
use Sdk\Module\Contracts\Bus\CommandBusInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

abstract class AbstractCreateBooking implements UseCaseInterface
{
    public function __construct(
        protected readonly CommandBusInterface $commandBus,
    ) {}

    //CreateHotelBookingDto|CreateAirportBooking|CreateTransferBooking
    protected function getOrderIdFromRequest($request): OrderId
    {
        $orderId = $request->orderId;
        if ($orderId === null) {
            $orderId = $this->commandBus->execute(
                new CreateOrder($request->clientId, $request->legalId, $request->currency)
            );
        }

        return new OrderId($orderId);
    }
}
