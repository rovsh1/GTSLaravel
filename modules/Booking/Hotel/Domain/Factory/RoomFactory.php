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
            new Price($data['price_net'], env('TRAVELINE_DEFAULT_CURRENCY_CODE')),
            $data['check_in_condition']['start'] ?? null,
            $data['check_out_condition']['end'] ?? null,
            $data['note'],
        );
    }
}
