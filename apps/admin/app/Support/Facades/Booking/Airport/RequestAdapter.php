<?php

declare(strict_types=1);

namespace App\Admin\Support\Facades\Booking\Airport;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void sendRequest(int $bookingId)
 * @method static array getBookingRequests(int $bookingId)
 * @method static mixed getDocumentFileInfo(int $requestId)
 **/
class RequestAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Admin\Support\Adapters\Booking\Airport\RequestAdapter::class;
    }
}
