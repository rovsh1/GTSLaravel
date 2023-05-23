<?php

declare(strict_types=1);

namespace Module\Booking\Order\Application\Query;

use Custom\Framework\Contracts\Bus\QueryHandlerInterface;
use Custom\Framework\Contracts\Bus\QueryInterface;
use Module\Booking\Order\Application\Dto\OrderDto;
use Module\Booking\Order\Infrastructure\Model\Order;

class GetActiveOrdersHandler implements QueryHandlerInterface
{
    public function handle(QueryInterface|GetActiveOrders $query): array
    {
        $orders = Order::query()->get()->all();

        return OrderDto::collection($orders)->all();
    }
}
