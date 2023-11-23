<?php

declare(strict_types=1);

namespace App\Admin\Support\Facades\Finance;

use Illuminate\Support\Facades\Facade;
use Module\Client\Invoicing\Application\Dto\OrderDto;

/**
 * @method static OrderDto[] getWaitingPaymentOrders(int $paymentId)
 **/
class OrderAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Admin\Support\Adapters\Finance\OrderAdapter::class;
    }
}
