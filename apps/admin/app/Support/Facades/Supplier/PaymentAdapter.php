<?php

declare(strict_types=1);

namespace App\Admin\Support\Facades\Supplier;

use Illuminate\Support\Facades\Facade;
use Module\Supplier\Payment\Application\Dto\PaymentDto;

/**
 * @method static PaymentDto|null get(int $paymentId)
 **/
class PaymentAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Admin\Support\Adapters\Supplier\PaymentAdapter::class;
    }
}
