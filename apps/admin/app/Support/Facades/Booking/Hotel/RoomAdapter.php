<?php

declare(strict_types=1);

namespace App\Admin\Support\Facades\Booking\Hotel;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array getAvailableRooms(int $bookingId)
 * @method static void addRoom(int $bookingId, int $roomId, int $rateId, bool $isResident, array|null $earlyCheckIn = null, array|null $lateCheckOut = null, string|null $note = null, int|null $discount = null)
 * @method static void updateRoom(int $bookingId, int $roomBookingId, int $roomId, int $rateId, bool $isResident, array|null $earlyCheckIn = null, array|null $lateCheckOut = null, string|null $note = null, int|null $discount = null)
 * @method static void deleteRoom(int $bookingId, int $roomBookingId)
 * @method static void bindRoomGuest(int $bookingId, int $roomBookingId, int $guestId)
 * @method static void unbindRoomGuest(int $bookingId, int $roomBookingId, int $guestId)
 * @method static void updateRoomPrice(int $bookingId, int $roomBookingId, float|null $grossPrice, float|null $netPrice)
 */
class RoomAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Admin\Support\Adapters\Booking\Hotel\RoomAdapter::class;
    }
}
