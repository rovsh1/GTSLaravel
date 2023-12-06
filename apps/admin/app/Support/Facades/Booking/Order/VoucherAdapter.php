<?php

declare(strict_types=1);

namespace App\Admin\Support\Facades\Booking\Order;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void create(int $orderId)
 * @method static void send(int $orderId)
 **/
class VoucherAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Admin\Support\Adapters\Booking\Order\VoucherAdapter::class;
    }
}
