<?php

declare(strict_types=1);

namespace Module\Booking\Order\Application\Query;

use Module\Booking\Order\Application\Dto\OrderDto;
use Module\Booking\Order\Domain\Repository\OrderRepositoryInterface;
use Sdk\Module\Contracts\Bus\QueryHandlerInterface;
use Sdk\Module\Contracts\Bus\QueryInterface;

class GetActiveOrdersHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly OrderRepositoryInterface $repository
    ) {}

    public function handle(QueryInterface|GetActiveOrders $query): array
    {
        $orders = $this->repository->getActiveOrders();

        return OrderDto::collectionFromDomain($orders);
    }
}
