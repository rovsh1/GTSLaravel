<?php

namespace GTS\Hotel\Domain\Factory;

use GTS\Hotel\Domain\Entity\PriceRate;
use Illuminate\Contracts\Support\Arrayable;

class RoomFactory extends EntityFactory
{
    public static function create(string $entityClass, null|array|Arrayable $data)
    {
        return new $entityClass(
            $data['id'],
            $data['display_name'],
            EntityFactory::create(PriceRate::class, $data['priceRate'])
        );
    }
}
