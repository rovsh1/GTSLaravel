<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\Order;

use Module\Booking\Moderation\Application\RequestDto\CreateOrderRequestDto;
use Module\Booking\Shared\Domain\Order\Repository\OrderRepositoryInterface;
use Module\Booking\Shared\Domain\Shared\Adapter\AdministratorAdapterInterface;
use Sdk\Booking\ValueObject\ClientId;
use Sdk\Booking\ValueObject\CreatorId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class CreateOrder implements UseCaseInterface
{
    public function __construct(
        private readonly OrderRepositoryInterface $repository,
        private readonly AdministratorAdapterInterface $administratorAdapter
    ) {}

    public function execute(CreateOrderRequestDto $request): int
    {
        $order = $this->repository->create(
            new ClientId($request->clientId),
            $request->currency,
            new CreatorId($request->creatorId),
            $request->legalId
        );
        $this->administratorAdapter->setOrderAdministrator($order->id(), $request->administratorId);

        //@todo ивенты созданного заказа
        return $order->id()->value();
    }
}
