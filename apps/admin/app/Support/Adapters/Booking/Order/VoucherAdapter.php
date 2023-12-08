<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Booking\Order;

use Module\Booking\Moderation\Application\Dto\VoucherDto;
use Module\Booking\Moderation\Application\UseCase\Order\Voucher\CreateVoucher;
use Module\Booking\Moderation\Application\UseCase\Order\Voucher\SendVoucher;

class VoucherAdapter
{
    public function create(int $orderId): VoucherDto
    {
        return app(CreateVoucher::class)->execute($orderId);
    }

    public function send(int $orderId): void
    {
        app(SendVoucher::class)->execute($orderId);
    }
}
