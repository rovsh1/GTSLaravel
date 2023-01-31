<?php

namespace GTS\Hotel\Infrastructure\DomainModelMapper;

use GTS\Hotel\Domain\Entity\Hotel;
use GTS\Hotel\Infrastructure\Models\Hotel as EloquentHotel;
use GTS\Shared\Infrastructure\DomainModelMapper\AbstractEloquentMapper;

class HotelModelMapper extends AbstractEloquentMapper
{

    /**
     * @param EloquentHotel $model
     * @return Hotel
     */
    public static function from($model): Hotel
    {
        return new Hotel($model->id, $model->name);
    }
}
