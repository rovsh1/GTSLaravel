<?php

declare(strict_types=1);

namespace App\Admin\Support\Facades\Booking;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array getAvailableActions(int $id)
 **/
class BookingAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Admin\Support\Adapters\Booking\BookingAdapter::class;
    }
}
