<?php

declare(strict_types=1);

namespace Module\Catalog\Infrastructure\Repository;

use Module\Catalog\Domain\Hotel\Factory\PriceRateFactory;
use Module\Catalog\Domain\Hotel\Repository\PriceRateRepositoryInterface;
use Module\Catalog\Domain\Hotel\ValueObject\HotelId;
use Module\Catalog\Infrastructure\Models\PriceRate;

class PriceRateRepository implements PriceRateRepositoryInterface
{
    public function __construct(
        private readonly PriceRateFactory $factory
    ) {}

    public function existsByRoomAndRate(int $roomId, int $rateId): bool
    {
        // TODO: Implement existsByRoomAndRate() method.
    }

    public function get(HotelId $hotelId): array
    {
        $models = PriceRate::whereHotelId($hotelId->value())->get();

        return $this->factory->createCollectionFrom($models);
    }
}
