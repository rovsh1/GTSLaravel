<?php

declare(strict_types=1);

namespace App\Admin\Models\Order;

use Sdk\Module\Database\Eloquent\HasQuicksearch;
use Sdk\Shared\Enum\Order\OrderStatusEnum;

class Order extends \Module\Booking\Shared\Infrastructure\Models\Order
{
    use HasQuicksearch;

    protected array $quicksearch = ['id'];

    public static function getActiveStatuses(): array
    {
        return [
            OrderStatusEnum::IN_PROGRESS,
            OrderStatusEnum::WAITING_INVOICE,
            OrderStatusEnum::INVOICED,
            OrderStatusEnum::PARTIAL_PAID,
        ];
    }
}
