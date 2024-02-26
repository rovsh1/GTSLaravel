<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Supplier;

use Module\Supplier\Payment\Application\Dto\PaymentDto;
use Module\Supplier\Payment\Application\UseCase\FindPayment;

class PaymentAdapter
{
    public function get(int $paymentId): ?PaymentDto
    {
        return app(FindPayment::class)->execute($paymentId);
    }
}
