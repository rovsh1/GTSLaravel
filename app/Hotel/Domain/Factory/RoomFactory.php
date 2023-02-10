<?php

namespace GTS\Hotel\Domain\Factory;

use Custom\Framework\Foundation\Support\EntityFactory\AbstractEntityFactory;
use GTS\Hotel\Domain\Entity\Room;

class RoomFactory extends AbstractEntityFactory
{
    protected string $entity = Room::class;

    public function toArray(mixed $data): array
    {
        return [
            'id' => $data->id,
            'name' => $data->display_name,
            'priceRates' => app(PriceRateFactory::class)->createCollectionFrom($data->priceRates),
            'guestsNumber' => $data->guests_number
        ];
    }
}
