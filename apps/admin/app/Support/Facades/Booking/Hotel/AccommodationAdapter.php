<?php

declare(strict_types=1);

namespace App\Admin\Support\Facades\Booking\Hotel;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array getAvailableRooms(int $bookingId)
 * @method static void add(int $bookingId, int $roomId, int $rateId, bool $isResident, array|null $earlyCheckIn = null, array|null $lateCheckOut = null, string|null $note = null, int|null $discount = null)
 * @method static void update(int $bookingId, int $accommodationId, int $roomId, int $rateId, bool $isResident, array|null $earlyCheckIn = null, array|null $lateCheckOut = null, string|null $note = null, int|null $discount = null)
 * @method static void delete(int $bookingId, int $accommodationId)
 * @method static void bindGuest(int $bookingId, int $accommodationId, int $guestId)
 * @method static void unbindGuest(int $bookingId, int $accommodationId, int $guestId)
 * @method static void updatePrice(int $bookingId, int $accommodationId, float|null $supplierDayPrice, float|null $clientDayPrice)
 */
class AccommodationAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Admin\Support\Adapters\Booking\Hotel\AccommodationAdapter::class;
    }
}
