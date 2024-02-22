<?php

namespace App\Admin\Support\Facades\Supplier;

use Illuminate\Support\Facades\Facade;
use Module\Supplier\Moderation\Application\Dto\CarDto;
use Module\Supplier\Moderation\Application\Response\CancelConditionsDto;

/**
 * @method static CarDto[] getCars(int $supplierId)
 * @method static CancelConditionsDto getCancelConditions(int $seasonId, int $serviceId, int $carId)
 * @method static void updateCancelConditions(int $seasonId, int $serviceId, int $carId, array $cancelConditions)
 */
class CarsAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Admin\Support\Adapters\Supplier\CarsAdapter::class;
    }
}
