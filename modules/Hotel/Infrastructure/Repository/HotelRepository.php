<?php

namespace Module\Hotel\Infrastructure\Repository;

use Module\Hotel\Domain\Entity\Hotel;
use Module\Hotel\Domain\Factory\HotelFactory;
use Module\Hotel\Domain\Repository\HotelRepositoryInterface;
use Module\Hotel\Infrastructure\Models\Hotel as HotelEloquent;
use Module\Shared\Domain\Service\SerializerInterface;

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
