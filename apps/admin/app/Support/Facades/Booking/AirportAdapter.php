<?php

declare(strict_types=1);

namespace App\Admin\Support\Facades\Booking;

use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Facade;

/**
 * @method static array getBookings(array $filters = [])
 * @method static mixed getBooking(int $id)
 * @method static int createBooking(int $cityId, int $clientId, int|null $legalId, int $currencyId, int $airportId, int $serviceId, CarbonInterface $date, int $creatorId, ?int $orderId, ?string $note = null)
 * @method static void addTourist(int $bookingId, string $fullName, int $countryId, int $gender, bool $isAdult, int|null $age)
 * @method static void updateTourist(int $bookingId, int $touristId, string $fullName, int $countryId, int $gender, bool $isAdult, int|null $age)
 * @method static void deleteTourist(int $bookingId, int $touristId)
 **/
class AirportAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Admin\Support\Adapters\Booking\AirportAdapter::class;
    }
}
