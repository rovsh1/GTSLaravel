<?php

namespace Module\Booking\Hotel\Domain\Factory;

use Custom\Framework\Foundation\Support\EntityFactory\AbstractEntityFactory;
use Module\Booking\Hotel\Domain\Entity\Room;
use Module\Booking\Hotel\Domain\ValueObject\Price;

class RoomFactory extends AbstractEntityFactory
{
    protected string $entity = Room::class;

    protected function fromArray(array $data): mixed
    {
        return new $this->entity(
            $data['room_id'],
            app(GuestFactory::class)->createCollectionFrom($data['guests']),
            $data['rate_id'],
            //@hack @todo тут должна быть валюта
            new Price($data['price_net'], 'UZS'),
            $data['check_in_condition']['start'] ?? null,
            $data['check_out_condition']['end'] ?? null,
            $data['note'],
        );
    }
}
