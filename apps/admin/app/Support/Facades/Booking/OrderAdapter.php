<?php

declare(strict_types=1);

namespace App\Admin\Support\Facades\Booking;

use Illuminate\Support\Facades\Facade;
use Module\Booking\Application\Dto\OrderAvailableActionsDto;
use Module\Booking\Application\Dto\StatusDto;

/**
 * @method static array getActiveOrders(int|null $clientId = null)
 * @method static mixed findOrder(int $id)
 * @method static mixed getGuests(int $orderId)
 * @method static mixed addGuest(int $orderId, string $fullName, int $countryId, int $gender, bool $isAdult, int|null $age)
 * @method static bool updateGuest(int $guestId, string $fullName, int $countryId, int $gender, bool $isAdult, int|null $age)
 * @method static void deleteGuest(int $guestId)
 * @method static StatusDto[] getStatuses()
 * @method static OrderAvailableActionsDto getAvailableActions(int $orderId)
 * @method static array getBookings(int $orderId)
 **/
class OrderAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Admin\Support\Adapters\Booking\OrderAdapter::class;
    }
}
