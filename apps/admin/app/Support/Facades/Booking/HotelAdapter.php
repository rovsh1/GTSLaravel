<?php

declare(strict_types=1);

namespace App\Admin\Support\Facades\Booking;

use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Facade;

/**
 * @method static array getBookings()
 * @method static int getBooking(int $id)
 * @method static int getBookingDetails(int $id)
 * @method static int createBooking(int $cityId, int $clientId, int $hotelId, CarbonPeriod $period, ?int $orderId, ?string $note = null)
 * @method static void addRoom(int $bookingId, int $roomId, int $rateId, int $status, bool $isResident, int $roomCount, array|null $earlyCheckIn = null, array|null $lateCheckOut = null, string|null $note = null, int|null $discount = null)
 * @method static void updateRoom(int $bookingId, int $roomIndex, int $roomId, int $rateId, int $status, bool $isResident, int $roomCount, array|null $earlyCheckIn = null, array|null $lateCheckOut = null, string|null $note = null, int|null $discount = null)
 * @method static void deleteRoom(int $bookingId, int $roomIndex)
 * @method static void addRoomGuest(int $bookingId, int $roomIndex, string $fullName, int $nationalityId, int $gender)
 * @method static void updateRoomGuest(int $bookingId, int $roomIndex, int $guestIndex, string $fullName, int $nationalityId, int $gender)
 **/
class HotelAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Admin\Support\Adapters\Booking\HotelAdapter::class;
    }
}
