<?php

declare(strict_types=1);

namespace App\Admin\Support\Facades\Hotel;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array getResidenceConditions(int $hotelId)
 */
class ResidenceConditionAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Admin\Support\Adapters\Hotel\ResidenceConditionAdapter::class;
    }
}
