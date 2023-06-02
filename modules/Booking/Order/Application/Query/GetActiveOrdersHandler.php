<?php

declare(strict_types=1);

namespace Module\Booking\Order\Application\Query;

use Module\Booking\Order\Application\Dto\OrderDto;
use Module\Booking\Order\Infrastructure\Models\Order;
use Sdk\Module\Contracts\Bus\QueryHandlerInterface;
use Sdk\Module\Contracts\Bus\QueryInterface;

class GetActiveOrdersHandler implements QueryHandlerInterface
{
    public function handle(QueryInterface|GetActiveOrders $query): array
    {
        $orders = Order::query()->get()->all();

        return OrderDto::collection($orders)->all();
    }
}
