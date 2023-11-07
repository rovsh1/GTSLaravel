<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Support\UseCase;

use Module\Booking\Shared\Domain\Order\Repository\OrderRepositoryInterface;
use Module\Booking\Shared\Domain\Order\ValueObject\ClientId;
use Module\Booking\Shared\Domain\Order\ValueObject\OrderId;
use Module\Booking\Shared\Domain\Shared\ValueObject\CreatorId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

abstract class AbstractCreateBooking implements UseCaseInterface
{
    public function __construct(
        private readonly OrderRepositoryInterface $repository
    ) {}

    //CreateHotelBookingDto|CreateAirportBooking|CreateTransferBooking
    protected function getOrderIdFromRequest($request): OrderId
    {
        $orderId = $request->orderId;
        if (null !== $orderId) {
            return new OrderId($orderId);
        }

        $order = $this->repository->create(
            new ClientId($request->clientId),
            $request->currency,
            new CreatorId($request->creatorId),
            $request->legalId
        );

        //@todo ивенты созданного заказа
        return $order->id();
    }
}
