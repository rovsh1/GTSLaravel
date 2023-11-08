<?php

namespace Module\Hotel\Moderation\Infrastructure\Repository;

use Module\Hotel\Moderation\Domain\Hotel\Factory\HotelFactory;
use Module\Hotel\Moderation\Domain\Hotel\Hotel;
use Module\Hotel\Moderation\Domain\Hotel\Repository\HotelRepositoryInterface;
use Module\Hotel\Moderation\Infrastructure\Models\Hotel as HotelEloquent;
use Module\Shared\Contracts\Service\SerializerInterface;

class HotelRepository implements HotelRepositoryInterface
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly HotelFactory $factory
    ) {}

    public function find(int $id): ?Hotel
    {
        $hotel = HotelEloquent::with(['contacts'])->find($id);

        return $this->factory->createFrom($hotel);
    }

    public function store(Hotel $hotel): bool
    {
        return (bool)HotelEloquent::whereId($hotel->id()->value())->update([
            'time_settings' => $this->serializer->serialize($hotel->timeSettings()),
        ]);
    }
}
