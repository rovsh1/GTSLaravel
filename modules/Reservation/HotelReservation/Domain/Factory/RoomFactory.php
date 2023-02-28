<?php

namespace Module\Reservation\HotelReservation\Domain\Factory;

use Custom\Framework\Foundation\Support\EntityFactory\AbstractEntityFactory;
use Module\Reservation\HotelReservation\Domain\Entity\Room;
use Module\Reservation\HotelReservation\Domain\ValueObject\Price;

class RoomFactory extends AbstractEntityFactory
{
    protected string $entity = Room::class;

    protected function fromArray(array $data): mixed
    {
        return new $this->entity(
            $data['room_id'],
            app(GuestFactory::class)->createCollectionFrom($data['guests']),
            $data['rate_id'],
            new Price($data['price_net'], env('DEFAULT_CURRENCY_CODE')),
            $data['check_in_condition']['start'] ?? null,
            $data['check_out_condition']['end'] ?? null,
            $data['note'],
        );
    }
}
