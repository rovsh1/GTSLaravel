<?php

declare(strict_types=1);

namespace App\Admin\Support\Facades\Booking\Service;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void updateDetailsField(int $bookingId, string $field, mixed $value)
 *
 * @method static void bindGuest(int $bookingId, int $guestId)
 * @method static void unbindGuest(int $bookingId, int $guestId)
 *
 * @method static void addCarBid(int $bookingId, array $carData)
 * @method static void updateCarBid(int $bookingId, string $carBidId, array $carData)
 * @method static void removeCarBid(int $bookingId, string $carBidId)
 */
class DetailsAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Admin\Support\Adapters\Booking\Service\DetailsAdapter::class;
    }
}
