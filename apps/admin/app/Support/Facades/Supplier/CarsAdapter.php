<?php

namespace App\Admin\Support\Facades\Supplier;

use Illuminate\Support\Facades\Facade;
use Module\Supplier\Application\Dto\CarDto;

/**
 * @method static CarDto[] getCars(int $supplierId)
 */
class CarsAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Admin\Support\Adapters\Supplier\CarsAdapter::class;
    }
}
