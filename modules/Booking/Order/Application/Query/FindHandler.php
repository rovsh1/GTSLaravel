<?php

namespace Module\Booking\Order\Application\Query;

use Custom\Framework\Contracts\Bus\QueryHandlerInterface;
use Custom\Framework\Contracts\Bus\QueryInterface;
use Module\Booking\Order\Application\Dto\OrderDto;
use Module\Booking\Order\Infrastructure\Model\Order;

class FindHandler implements QueryHandlerInterface
{
    public function handle(QueryInterface|Find $query): ?OrderDto
    {
        $model = Order::find($query->id);
        if (!$model) {
            return null;
        }
        return OrderDto::from($model);
    }
}
