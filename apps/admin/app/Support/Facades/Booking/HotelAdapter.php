<?php

declare(strict_types=1);

namespace App\Admin\Support\Facades\Booking;

use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Facade;

/**
 * @method static array getBookings()
 * @method static mixed getBooking(int $id)
 * @method static int createBooking(int $cityId, int $clientId, int $hotelId, CarbonPeriod $period, int $creatorId, ?int $orderId, ?string $note = null)
 * @method static void addRoom(int $bookingId, int $roomId, int $rateId, int $status, bool $isResident, array|null $earlyCheckIn = null, array|null $lateCheckOut = null, string|null $note = null, int|null $discount = null)
 * @method static void updateRoom(int $bookingId, int $roomIndex, int $roomId, int $rateId, int $status, bool $isResident, array|null $earlyCheckIn = null, array|null $lateCheckOut = null, string|null $note = null, int|null $discount = null)
 * @method static void deleteRoom(int $bookingId, int $roomIndex)
 * @method static void addRoomGuest(int $bookingId, int $roomIndex, string $fullName, int $countryId, int $gender, bool $isAdult)
 * @method static void updateRoomGuest(int $bookingId, int $roomIndex, int $guestIndex, string $fullName, int $countryId, int $gender, bool $isAdult)
 * @method static void updateExternalNumber(int $bookingId, int $type, string|null $number)
 **/
class HotelAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Admin\Support\Adapters\Booking\HotelAdapter::class;
    }
}
