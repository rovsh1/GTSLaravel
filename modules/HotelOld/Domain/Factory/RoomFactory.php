<?php

namespace Module\HotelOld\Domain\Factory;

use Module\HotelOld\Domain\Entity\Room;
use Sdk\Module\Foundation\Support\EntityFactory\AbstractEntityFactory;

class RoomFactory extends AbstractEntityFactory
{
    protected string $entity = Room::class;

    protected function fromArray(array $data): mixed
    {
        return new $this->entity(
            $data['id'],
            $data['display_name'],
            app(PriceRateFactory::class)->createCollectionFrom($data['price_rates']),
            $data['guests_number']
        );
    }
}
