<?php

declare(strict_types=1);

namespace App\Hotel\Support\Adapters\Booking;

use Module\Booking\Moderation\Application\Dto\OrderDto;
use Module\Booking\Moderation\Application\UseCase\Order\GetOrder;
use Module\Booking\Moderation\Application\UseCase\Order\Guest\Get;

class OrderAdapter
{
    public function getOrder(int $id): ?OrderDto
    {
        return app(GetOrder::class)->execute($id);
    }

    public function getGuests(int $orderId): array
    {
        return app(Get::class)->execute($orderId);
    }
}
