<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\Order\UseCase;

use Module\Booking\Application\Admin\Order\Query\Find as Query;
use Module\Booking\Application\Admin\Order\Response\OrderDto;
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
