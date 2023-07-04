<?php

declare(strict_types=1);

namespace App\Admin\Support\Facades\Booking;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void sendVoucher(int $bookingId)
 * @method static array getBookingVouchers(int $bookingId)
 **/
class VoucherAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Admin\Support\Adapters\Booking\VoucherAdapter::class;
    }
}
