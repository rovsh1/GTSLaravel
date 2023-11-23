<?php

declare(strict_types=1);

namespace App\Admin\Support\Facades\Finance;

use Illuminate\Support\Facades\Facade;
use Module\Client\Invoicing\Application\Dto\OrderDto;

/**
 * @method static OrderDto[] getWaitingPaymentOrders(int $paymentId)
 * @method static OrderDto[] getPaymentOrders(int $paymentId)
 * @method static void lendOrders(int $paymentId, array $orders)
 **/
class OrderAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Admin\Support\Adapters\Finance\OrderAdapter::class;
    }
}
