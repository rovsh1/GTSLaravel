<?php

namespace Module\Catalog\Domain\Hotel\Repository;

use Module\Catalog\Domain\Hotel\Entity\PriceRate;
use Module\Catalog\Domain\Hotel\ValueObject\HotelId;

interface PriceRateRepositoryInterface
{
    public function existsByRoomAndRate(int $roomId, int $rateId): bool;

    /**
     * @param HotelId $hotelId
     * @return PriceRate[]
     */
    public function get(HotelId $hotelId): array;
}
