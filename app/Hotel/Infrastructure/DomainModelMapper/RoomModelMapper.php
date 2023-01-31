<?php

namespace GTS\Hotel\Infrastructure\DomainModelMapper;

use GTS\Hotel\Domain\Entity\Room;
use GTS\Hotel\Infrastructure\Models\Room as EloquentRoom;
use GTS\Shared\Infrastructure\DomainModelMapper\AbstractEloquentMapper;

class RoomModelMapper extends AbstractEloquentMapper
{

    /**
     * @param EloquentRoom $model
     * @return Room
     */
    public static function from($model): Room
    {
        return new Room($model->id, $model->front_name);
    }
}
