<?php

declare(strict_types=1);

namespace Module\Booking\Order\Application\UseCase;

use Module\Booking\Order\Application\Query\GetActiveOrders as Query;
use Sdk\Module\Contracts\Bus\QueryBusInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetActiveOrders implements UseCaseInterface
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
    ) {}

    public function execute(): array
    {
        return $this->queryBus->execute(new Query());
    }
}
