<?php

declare(strict_types=1);

namespace App\Admin\Support\Facades\Booking;

use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Facade;

/**
 * @method static array getBookings()
 * @method static Builder getBookingQuery()
 * @method static mixed getBooking(int $id)
 * @method static int copyBooking(int $id)
 * @method static void deleteBooking(int $id)
 * @method static void bulkDeleteBookings(int[] $id)
 * @method static int createBooking(int $cityId, int $clientId, int|null $legalId, int $currencyId, int $hotelId, CarbonPeriod $period, int $creatorId, int $quotaProcessingMethod, ?int $orderId, ?string $note = null)
 * @method static array getAvailableActions(int $id)
 * @method static void updateBooking(int $id, CarbonPeriod $period, ?string $note = null)
 * @method static void addRoom(int $bookingId, int $roomId, int $rateId, bool $isResident, array|null $earlyCheckIn = null, array|null $lateCheckOut = null, string|null $note = null, int|null $discount = null)
 * @method static void updateRoom(int $bookingId, int $roomBookingId, int $roomId, int $rateId, bool $isResident, array|null $earlyCheckIn = null, array|null $lateCheckOut = null, string|null $note = null, int|null $discount = null)
 * @method static void deleteRoom(int $bookingId, int $roomBookingId)
 * @method static void bindRoomGuest(int $bookingId, int $roomBookingId, int $guestId)
 * @method static void unbindRoomGuest(int $bookingId, int $roomBookingId, int $guestId)
 * @method static void updateExternalNumber(int $bookingId, int $type, string|null $number)
 * @method static void updateNote(int $bookingId, string|null $note)
 * @method static array getStatuses()
 * @method static mixed updateStatus(int $id, int $status, string|null $notConfirmedReason = null, float|null $cancelFeeAmount = null)
 * @method static array getStatusHistory(int $id)
 **/
class HotelAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Admin\Support\Adapters\Booking\HotelAdapter::class;
    }
}
