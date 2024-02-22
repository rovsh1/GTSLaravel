<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Service;

use Module\Booking\Moderation\Application\RequestDto\CreateBookingRequestDto;
use Module\Booking\Shared\Domain\Order\DbContext\OrderDbContextInterface;
use Module\Booking\Shared\Domain\Order\Order;
use Module\Booking\Shared\Domain\Shared\Adapter\AdministratorAdapterInterface;
use Sdk\Booking\ValueObject\ClientId;
use Sdk\Booking\ValueObject\CreatorId;

class OrderFactory
{
    public function __construct(
        private readonly OrderDbContextInterface $orderDbContext,
        private readonly AdministratorAdapterInterface $administratorAdapter
    ) {}

    public function createFromBookingRequest(CreateBookingRequestDto $request): Order
    {
        $order = $this->orderDbContext->create(
            new ClientId($request->clientId),
            $request->currency,
            new CreatorId($request->creatorId),
            $request->legalId
        );
        $this->administratorAdapter->setOrderAdministrator($order->id(), $request->administratorId);

        //@todo ивенты созданного заказа
        return $order;
    }
}
