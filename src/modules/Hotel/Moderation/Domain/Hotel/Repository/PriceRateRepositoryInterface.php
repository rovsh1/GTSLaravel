<?php

namespace Module\Hotel\Moderation\Domain\Hotel\Repository;

use Module\Hotel\Moderation\Domain\Hotel\Entity\PriceRate;
use Module\Hotel\Moderation\Domain\Hotel\ValueObject\HotelId;

interface PriceRateRepositoryInterface
{
    public function existsByRoomAndRate(int $roomId, int $rateId): bool;

    /**
     * @param HotelId $hotelId
     * @return PriceRate[]
     */
    public function get(HotelId $hotelId): array;
}
