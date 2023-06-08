<?php

declare(strict_types=1);

namespace App\Admin\Support\Facades\Booking;

use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Facade;

/**
 * @method static array getStatuses()
 * @method static mixed updateStatus(int $id, int $status, string|null $notConfirmedReason = null)
 * @method static array getStatusHistory(int $id)
 **/
class StatusAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Admin\Support\Adapters\Booking\StatusAdapter::class;
    }
}
