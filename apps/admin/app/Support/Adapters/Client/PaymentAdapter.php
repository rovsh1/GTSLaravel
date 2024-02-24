<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Client;

use Module\Client\Payment\Application\Dto\PaymentDto;
use Module\Client\Payment\Application\UseCase\FindPayment;

class PaymentAdapter
{
    public function get(int $paymentId): ?PaymentDto
    {
        return app(FindPayment::class)->execute($paymentId);
    }
}
