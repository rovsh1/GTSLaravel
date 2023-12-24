<?php

declare(strict_types=1);

namespace App\Hotel\Support\Facades\Booking;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array getBookingRequests(int $bookingId)
 * @method static mixed getDocumentFileInfo(int $requestId)
 **/
class RequestAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Hotel\Support\Adapters\Booking\RequestAdapter::class;
    }
}
