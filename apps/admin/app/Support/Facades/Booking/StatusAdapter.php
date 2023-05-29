<?php

declare(strict_types=1);

namespace App\Admin\Support\Facades\Booking;

use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Facade;

/**
 * @method static array getStatuses()
 * @method static array getAvailableStatuses(int $id)
 * @method static void updateStatus(int $id, int $status)
 **/
class StatusAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Admin\Support\Adapters\Booking\StatusAdapter::class;
    }
}
