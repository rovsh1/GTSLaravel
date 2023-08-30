<?php

declare(strict_types=1);

namespace App\Admin\Support\Facades\Booking;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array getActiveOrders(int|null $clientId = null)
 * @method static mixed findOrder(int $id)
 * @method static mixed getGuests(int $orderId)
 * @method static mixed addGuest(int $orderId, string $fullName, int $countryId, int $gender, bool $isAdult, int|null $age)
 * @method static bool updateGuest(int $guestId, string $fullName, int $countryId, int $gender, bool $isAdult, int|null $age)
 * @method static void deleteGuest(int $guestId)
 **/
class OrderAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Admin\Support\Adapters\Booking\OrderAdapter::class;
    }
}
