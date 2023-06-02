<?php

namespace Module\Booking\Order\Application\Query;

use Module\Booking\Order\Application\Dto\OrderDto;
use Module\Booking\Order\Infrastructure\Models\Order;
use Sdk\Module\Contracts\Bus\QueryHandlerInterface;
use Sdk\Module\Contracts\Bus\QueryInterface;

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
