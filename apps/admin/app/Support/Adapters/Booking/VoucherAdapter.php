<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Booking;

use Module\Booking\Application\UseCase\Admin\Order\Voucher\GetDocumentFileInfo;
use Module\Booking\Application\UseCase\Admin\Order\Voucher\GetVouchers;
use Module\Booking\Application\UseCase\Admin\Order\Voucher\SendVoucher;

class VoucherAdapter
{
    public function sendVoucher(int $bookingId): void
    {
        app(SendVoucher::class)->execute($bookingId);
    }

    public function getBookingVouchers(int $bookingId): array
    {
        return app(GetVouchers::class)->execute($bookingId);
    }

    public function getDocumentFileInfo(int $voucherId): mixed
    {
        return app(GetDocumentFileInfo::class)->execute($voucherId);
    }
}
