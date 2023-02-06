<?php

namespace GTS\Hotel\Domain\Factory;

use GTS\Hotel\Domain\Entity\Room;
use GTS\Shared\Domain\Factory\AbstractEntityFactory;
use GTS\Shared\Domain\Factory\EntityCollectionOf;

class RoomFactory extends AbstractEntityFactory
{
    public static string $entity = Room::class;

    public function __construct(
        public readonly int              $id,
        public readonly string           $name,
        /** @var PriceRateFactory[] $priceRates */
        #[EntityCollectionOf(PriceRateFactory::class)]
        public readonly array $priceRates
    ) {}

    public static function fromModel(\GTS\Hotel\Infrastructure\Models\Room $room)
    {
        //@todo доменная модель стала зависимой от Eloquent модели
        return new self(
            $room->id,
            $room->display_name,
            PriceRateFactory::createCollectionFrom($room->priceRates)
        );
    }
}
