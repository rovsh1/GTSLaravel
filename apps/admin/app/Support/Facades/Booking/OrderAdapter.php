<?php

declare(strict_types=1);

namespace App\Admin\Support\Facades\Booking;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array getActiveOrders(int|null $clientId = null)
 * @method static mixed findOrder(int $id)
 * @method static mixed getTourists(int $orderId)
 * @method static mixed addTourist(int $orderId, string $fullName, int $countryId, int $gender, bool $isAdult, int|null $age)
 * @method static bool updateTourist(int $touristId, string $fullName, int $countryId, int $gender, bool $isAdult, int|null $age)
 * @method static void deleteTourist(int $touristId)
 **/
class OrderAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Admin\Support\Adapters\Booking\OrderAdapter::class;
    }
}
