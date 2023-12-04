<?php

declare(strict_types=1);

namespace App\Admin\Support\Facades\Booking;

use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Facade;
use Module\Booking\Moderation\Application\Dto\OrderAvailableActionsDto;
use Module\Booking\Moderation\Application\Dto\OrderDto;
use Module\Booking\Moderation\Application\ResponseDto\OrderBookingDto;
use Sdk\Booking\Dto\StatusDto;
use Sdk\Shared\Enum\CurrencyEnum;

/**
 * @method static OrderDto[] getActiveOrders(int|null $clientId = null)
 * @method static OrderDto|null getOrder(int $id)
 * @method static mixed getGuests(int $orderId)
 * @method static mixed addGuest(int $orderId, string $fullName, int $countryId, int $gender, bool $isAdult, int|null $age)
 * @method static bool updateGuest(int $guestId, string $fullName, int $countryId, int $gender, bool $isAdult, int|null $age)
 * @method static void deleteGuest(int $guestId)
 * @method static StatusDto[] getStatuses()
 * @method static OrderAvailableActionsDto getAvailableActions(int $orderId)
 * @method static OrderBookingDto[] getBookings(int $orderId)
 * @method static void updateStatus(int $orderId, int $status)
 * @method static int create(int $clientId, int|null $legalId, CurrencyEnum $currency, CarbonPeriod $period, int $managerId, int $creatorId)
 **/
class OrderAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Admin\Support\Adapters\Booking\OrderAdapter::class;
    }
}
