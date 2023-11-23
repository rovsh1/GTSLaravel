<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Finance;

use Module\Client\Payment\Application\RequestDto\LendPaymentRequestDto;
use Module\Client\Payment\Application\UseCase\LendPayment;

class PaymentAdapter
{
    public function lendPayment(int $paymentId, int $orderId, float $sum): void
    {
        app(LendPayment::class)->execute(
            new LendPaymentRequestDto(
                paymentId: $paymentId,
                orderId: $orderId,
                sum: $sum
            )
        );
    }
}
