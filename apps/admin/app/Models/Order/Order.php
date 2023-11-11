<?php

declare(strict_types=1);

namespace App\Admin\Models\Order;

use Sdk\Module\Database\Eloquent\HasQuicksearch;

class Order extends \Module\Booking\Shared\Infrastructure\Models\Order
{
    use HasQuicksearch;

    protected array $quicksearch = ['id'];
}
