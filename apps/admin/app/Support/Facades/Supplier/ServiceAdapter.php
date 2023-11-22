<?php

namespace App\Admin\Support\Facades\Supplier;

use Illuminate\Support\Facades\Facade;
use Module\Supplier\Moderation\Application\Dto\CarDto;
use Module\Supplier\Moderation\Application\Response\CancelConditionsDto;

/**
 * @method static CancelConditionsDto getCancelConditions(int $seasonId, int $serviceId)
 * @method static void updateCancelConditions(int $seasonId, int $serviceId, array $cancelConditions)
 */
class ServiceAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Admin\Support\Adapters\Supplier\ServiceAdapter::class;
    }
}
