<?php

declare(strict_types=1);

namespace App\Admin\Support\Facades\Supplier;

use Illuminate\Support\Facades\Facade;
use Module\Client\Invoicing\Application\Dto\OrderDto;

/**
 * @method static OrderDto[] getWaitingPaymentBookings(int $paymentId)
 * @method static OrderDto[] getPaymentBookings(int $paymentId)
 * @method static void lendBookings(int $paymentId, array $bookings)
 **/
class BookingAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Admin\Support\Adapters\Supplier\BookingAdapter::class;
    }
}
