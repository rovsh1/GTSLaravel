<?php

declare(strict_types=1);

namespace App\Admin\Support\Facades\Booking;

use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Facade;

/**
 * @method static array getBookings(array $filters = [])
 * @method static mixed getBooking(int $id)
 * @method static int createBooking(int $cityId, int $clientId, int $airportId, int $serviceId, CarbonInterface $date, int $creatorId, ?int $orderId, ?string $note = null)
 * @method static void addTourist(int $bookingId, string $fullName, int $countryId, int $gender, bool $isAdult)
 * @method static void updateTourist(int $bookingId, int $touristIndex, string $fullName, int $countryId, int $gender, bool $isAdult)
 **/
class AirportAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Admin\Support\Adapters\Booking\AirportAdapter::class;
    }
}
