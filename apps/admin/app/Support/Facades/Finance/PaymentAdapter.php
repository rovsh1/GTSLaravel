<?php

declare(strict_types=1);

namespace App\Admin\Support\Facades\Finance;

use Illuminate\Support\Facades\Facade;
use Module\Client\Payment\Application\Dto\PaymentDto;

/**
 * @method static PaymentDto|null get(int $paymentId)
 **/
class PaymentAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Admin\Support\Adapters\Finance\PaymentAdapter::class;
    }
}
