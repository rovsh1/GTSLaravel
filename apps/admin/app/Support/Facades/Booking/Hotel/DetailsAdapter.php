<?php

declare(strict_types=1);

namespace App\Admin\Support\Facades\Booking\Hotel;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void updateExternalNumber(int $bookingId, int $type, string|null $number)
 **/
class DetailsAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Admin\Support\Adapters\Booking\Hotel\DetailsAdapter::class;
    }
}
