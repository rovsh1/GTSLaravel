<?php

declare(strict_types=1);

namespace Module\Booking\Order\Application\UseCase\Admin;

use Module\Booking\Order\Application\Response\OrderDto;
use Module\Booking\Order\Application\Query\Find as Query;
use Sdk\Module\Contracts\Bus\QueryBusInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetOrder implements UseCaseInterface
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
    ) {}

    public function execute(int $id): OrderDto
    {
        return $this->queryBus->execute(new Query($id));
    }
}
