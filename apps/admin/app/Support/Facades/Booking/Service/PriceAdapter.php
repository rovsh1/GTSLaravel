<?php

declare(strict_types=1);

namespace App\Admin\Support\Facades\Booking\Service;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void setGrossPrice(int $bookingId, float $price)
 * @method static void setNetPrice(int $bookingId, float $price)
 * @method static void setCalculatedGrossPrice(int $bookingId)
 * @method static void setCalculatedNetPrice(int $bookingId)
 * @method static void setNetPenalty(int $bookingId, float|null $penalty)
 * @method static void setGrossPenalty(int $bookingId, float|null $penalty)
 */
class PriceAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Admin\Support\Adapters\Booking\Service\PriceAdapter::class;
    }
}
