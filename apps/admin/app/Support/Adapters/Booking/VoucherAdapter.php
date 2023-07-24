<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Booking;

use Module\Booking\HotelBooking\Application\UseCase\Admin\Voucher\GetBookingVouchers;
use Module\Booking\HotelBooking\Application\UseCase\Admin\Voucher\GetDocumentFileInfo;
use Module\Booking\HotelBooking\Application\UseCase\Admin\Voucher\SendVoucher;

class VoucherAdapter
{
    public function sendVoucher(int $bookingId): void
    {
        app(SendVoucher::class)->execute($bookingId);
    }

    public function getBookingVouchers(int $bookingId): array
    {
        return app(GetBookingVouchers::class)->execute($bookingId);
    }

    public function getDocumentFileInfo(int $voucherId): mixed
    {
        return app(GetDocumentFileInfo::class)->execute($voucherId);
    }
}
