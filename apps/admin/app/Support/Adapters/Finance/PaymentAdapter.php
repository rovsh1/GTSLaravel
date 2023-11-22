<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Finance;

use Module\Client\Payment\Application\RequestDto\PantPaymentRequestDto;
use Module\Client\Payment\Application\UseCase\PantPayment;

class PaymentAdapter
{
    public function lendPayment(int $paymentId, int $orderId, float $sum): void
    {
        app(PantPayment::class)->execute(
            new PantPaymentRequestDto(
                paymentId: $paymentId,
                orderId: $orderId,
                sum: $sum
            )
        );
    }
}
