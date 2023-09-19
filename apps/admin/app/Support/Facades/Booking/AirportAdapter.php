<?php

declare(strict_types=1);

namespace App\Admin\Support\Facades\Booking;

use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Facade;

/**
 * @method static array getBookings(array $filters = [])
 * @method static mixed getBooking(int $id)
 * @method static array getAvailableActions(int $id)
 * @method static int createBooking(int $cityId, int $clientId, int|null $legalId, int $currencyId, int $airportId, int $serviceId, CarbonInterface $date, string $flightNumber, int $creatorId, ?int $orderId, ?string $note = null)
 * @method static void bindGuest(int $bookingId, int $guestId)
 * @method static void unbindGuest(int $bookingId, int $guestId)
 * @method static array getStatuses()
 * @method static mixed updateStatus(int $id, int $status, string|null $notConfirmedReason = null, float|null $cancelFeeAmount = null)
 * @method static array getStatusHistory(int $id)
 * @method static void updateNote(int $bookingId, string|null $note)
 * @method static int copyBooking(int $id)
 * @method static void deleteBooking(int $id)
 * @method static void bulkDeleteBookings(int[] $id)
 **/
class AirportAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Admin\Support\Adapters\Booking\AirportAdapter::class;
    }
}
