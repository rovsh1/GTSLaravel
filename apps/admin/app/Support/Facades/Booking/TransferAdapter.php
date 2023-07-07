<?php

declare(strict_types=1);

namespace App\Admin\Support\Facades\Booking;

use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Facade;

/**
 * @method static array getBookings(array $filters = [])
 * @method static mixed getBooking(int $id)
 * @method static int createBooking(int $cityId, int $clientId, int|null $legalId, int $currencyId, int $serviceId, CarbonInterface $date, int $creatorId, ?int $orderId, ?string $note = null)
 **/
class TransferAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Admin\Support\Adapters\Booking\TransferAdapter::class;
    }
}
