<?php

namespace Module\Hotel\Moderation\Domain\Hotel\Factory;

use Module\Hotel\Moderation\Domain\Hotel\Entity\Room;
use Module\Hotel\Moderation\Domain\Hotel\ValueObject\HotelId;
use Module\Hotel\Moderation\Domain\Hotel\ValueObject\RoomId;
use Sdk\Module\Foundation\Support\EntityFactory\AbstractEntityFactory;

class RoomFactory extends AbstractEntityFactory
{
    protected string $entity = Room::class;

    protected function fromArray(array $data): mixed
    {
        return new $this->entity(
            new RoomId($data['id']),
            new HotelId($data['hotel_id']),
            $data['name'],
            app(PriceRateFactory::class)->createCollectionFrom($data['price_rates']),
            $data['guests_count'],
            $data['rooms_number'],
        );
    }
}
