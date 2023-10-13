<?php

declare(strict_types=1);

namespace App\Admin\Support\Facades\Booking;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void setManualClientPrice(int $bookingId, float|null $price)
 * @method static void setManualSupplierPrice(int $bookingId, float|null $price)
 * @method static void setSupplierPenalty(int $bookingId, float|null $penalty)
 * @method static void setClientPenalty(int $bookingId, float|null $penalty)
 */
class PriceAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Admin\Support\Adapters\Booking\PriceAdapter::class;
    }
}
