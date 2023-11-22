<?php

declare(strict_types=1);

namespace App\Admin\Support\Facades\Finance;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void lendPayment(int $paymentId, int $orderId, float $sum)
 **/
class PaymentAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Admin\Support\Adapters\Finance\PaymentAdapter::class;
    }
}
