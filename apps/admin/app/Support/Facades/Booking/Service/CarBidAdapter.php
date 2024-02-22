<?php

declare(strict_types=1);

namespace App\Admin\Support\Facades\Booking\Service;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void addCarBid(int $bookingId, array $carData)
 * @method static void updateCarBid(int $bookingId, int $carBidId, array $carData)
 * @method static void removeCarBid(int $bookingId, int $carBidId)
 *
 * @method static void bindGuest(int $bookingId, int $carBidId, int $guestId)
 * @method static void unbindGuest(int $bookingId, int $carBidId, int $guestId)
 * @method static void setManualPrice(int $bookingId, int $carBidId, float|null $clientPerCarPrice, float|null $supplierPerCarPrice = null)
 */
class CarBidAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Admin\Support\Adapters\Booking\Service\CarBidAdapter::class;
    }
}
