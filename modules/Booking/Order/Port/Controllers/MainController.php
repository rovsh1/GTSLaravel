<?php

declare(strict_types=1);

namespace Module\Booking\Order\Port\Controllers;

use Custom\Framework\Contracts\Bus\CommandBusInterface;
use Custom\Framework\Contracts\Bus\QueryBusInterface;
use Custom\Framework\PortGateway\Request;
use Module\Booking\Order\Application\Command\CreateOrder;
use Module\Booking\Order\Application\Query\Find;
use Module\Booking\Order\Application\Query\GetActiveOrders;

class MainController
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
        private readonly CommandBusInterface $commandBus
    ) {}

    public function getActiveOrders(Request $request): array
    {
        $request->validate([
            //
        ]);

        return $this->queryBus->execute(new GetActiveOrders());
    }

    public function createOrder(Request $request): int
    {
        $request->validate([
            'clientId' => ['required', 'int'],
        ]);

        return $this->commandBus->execute(new CreateOrder($request->clientId));
    }

    public function getOrder(Request $request): mixed
    {
        $request->validate([
            'id' => ['required', 'int']
        ]);

        return $this->queryBus->execute(new Find($request->id));
    }
}
