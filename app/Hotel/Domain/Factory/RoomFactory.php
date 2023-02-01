<?php

namespace GTS\Hotel\Domain\Factory;

use Illuminate\Contracts\Support\Arrayable;

class RoomFactory extends EntityFactory
{
    public static function create(string $entityClass, array|Arrayable $data)
    {
        return new $entityClass(
            $data['id'],
            $data['front_name'],
        );
    }
}
