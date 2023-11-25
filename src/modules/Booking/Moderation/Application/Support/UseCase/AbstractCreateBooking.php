<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Support\UseCase;

use Module\Booking\Moderation\Application\RequestDto\CreateBookingRequestDto;
use Module\Booking\Shared\Domain\Order\Repository\OrderRepositoryInterface;
use Module\Booking\Shared\Domain\Shared\Adapter\AdministratorAdapterInterface;
use Sdk\Booking\ValueObject\ClientId;
use Sdk\Booking\ValueObject\CreatorId;
use Sdk\Booking\ValueObject\OrderId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

abstract class AbstractCreateBooking implements UseCaseInterface
{
    public function __construct(
        private readonly OrderRepositoryInterface $repository,
        protected readonly AdministratorAdapterInterface $administratorAdapter
    ) {}

    protected function getOrderIdFromRequest(CreateBookingRequestDto $request): OrderId
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
        $this->administratorAdapter->setOrderAdministrator($order->id(), $request->administratorId);

        //@todo ивенты созданного заказа
        return $order->id();
    }
}
