<?php

declare(strict_types=1);

namespace App\Hotel\Support\Facades\Booking;

use Illuminate\Support\Facades\Facade;
use Module\Booking\Moderation\Application\Dto\OrderDto;

/**
 * @method static OrderDto|null getOrder(int $id)
 **/
class OrderAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Hotel\Support\Adapters\Booking\OrderAdapter::class;
    }
}
