<?php

declare(strict_types=1);

namespace App\Admin\Support\Facades\Booking;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Facade;
use Module\Shared\Enum\CurrencyEnum;
use Module\Shared\Enum\ServiceTypeEnum;

/**
 * @method static array getStatuses()
 * @method static mixed getBooking(int $id)
 * @method static int createBooking(int $clientId, int|null $legalId, CurrencyEnum $currency, int $serviceId, int $creatorId, ?int $orderId, array|null $detailsData, string|null $note = null)
 * @method static array getAvailableActions(int $id)
 * @method static mixed updateStatus(int $id, int $status, string|null $notConfirmedReason = null, float|null $netPenalty = null)
 * @method static array getStatusHistory(int $id)
 * @method static void updateNote(int $bookingId, string|null $note)
 * @method static int copyBooking(int $id)
 * @method static void deleteBooking(int $id)
 * @method static void bulkDeleteBookings(int[] $id)
 * @method static void updateDetailsField(int $bookingId, string $field, mixed $value)
 *
 * @method static void bindGuest(int $bookingId, int $guestId)
 * @method static void unbindGuest(int $bookingId, int $guestId)
 *
 * @method static void addCarBid(int $bookingId, array $carData)
 * @method static void updateCarBid(int $bookingId, array $carData)
 * @method static void removeCarBid(int $bookingId, array $carData)
 **/
class ServiceAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Admin\Support\Adapters\Booking\ServiceAdapter::class;
    }
}
