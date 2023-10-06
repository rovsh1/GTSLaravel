<?php

namespace Module\Pricing\Domain\Hotel\UseCase;

use Module\Pricing\Domain\Hotel\Repository\HotelRepositoryInterface;
use Module\Pricing\Domain\Hotel\Support\FormulaUtil;
use Module\Pricing\Domain\Hotel\ValueObject\RoomId;

final class CalculateRoomDayPrice
{
    public function __construct(
        private readonly HotelRepositoryInterface $hotelRepository
    ) {
    }

    /**
     * Po + NDS(Po) + T(BZV) * n
     *
     * @param int|float $Po
     * @param RoomId $roomId
     * @param bool $isResident
     * @param int $guestCount
     * @return float
     *
     * @see /docs/app/price-calculation.md
     */
    public function execute(
        int|float $Po,
        RoomId $roomId,
        bool $isResident,
        int $guestCount
    ): float {
        $hotel = $this->hotelRepository->findByRoomId($roomId);

        return $Po
            + FormulaUtil::calculatePercent($Po, $hotel->vat()->value())
            + FormulaUtil::calculatePercent($Po, $hotel->touristTax($isResident)->value()) * $guestCount;
    }
}
