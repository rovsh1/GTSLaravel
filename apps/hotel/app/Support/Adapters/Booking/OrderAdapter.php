<?php

declare(strict_types=1);

namespace App\Hotel\Support\Adapters\Booking;

use Module\Booking\Moderation\Application\Dto\OrderDto;
use Module\Booking\Moderation\Application\UseCase\Order\GetOrder;

class OrderAdapter
{
    public function getOrder(int $id): ?OrderDto
    {
        return app(GetOrder::class)->execute($id);
    }
}