<?php

namespace Module\Hotel\Domain\Factory;

use Custom\Framework\Foundation\Support\EntityFactory\AbstractEntityFactory;
use Module\Hotel\Domain\Entity\Room;

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
