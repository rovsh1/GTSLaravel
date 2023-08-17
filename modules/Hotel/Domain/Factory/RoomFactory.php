<?php

namespace Module\Hotel\Domain\Factory;

use Module\Hotel\Domain\Entity\Room;
use Sdk\Module\Foundation\Support\EntityFactory\AbstractEntityFactory;

class RoomFactory extends AbstractEntityFactory
{
    protected string $entity = Room::class;

    protected function fromArray(array $data): mixed
    {
        return new $this->entity(
            $data['id'],
            $data['name'],
            app(PriceRateFactory::class)->createCollectionFrom($data['price_rates']),
            $data['guests_count']
        );
    }
}
