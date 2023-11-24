<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Finance;

use Module\Client\Payment\Application\Dto\PaymentDto;
use Module\Client\Payment\Application\RequestDto\LendPaymentRequestDto;
use Module\Client\Payment\Application\UseCase\FindPayment;
use Module\Client\Payment\Application\UseCase\LendPayment;

class PaymentAdapter
{
    public function get(int $paymentId): ?PaymentDto
    {
        return app(FindPayment::class)->execute($paymentId);
    }

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
