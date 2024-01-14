<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Infrastructure\Repository;

use Illuminate\Support\Facades\DB;
use Module\Hotel\Moderation\Domain\Hotel\Factory\PriceRateFactory;
use Module\Hotel\Moderation\Domain\Hotel\Repository\PriceRateRepositoryInterface;
use Module\Hotel\Moderation\Domain\Hotel\ValueObject\HotelId;
use Module\Hotel\Moderation\Infrastructure\Models\PriceRate;

class PriceRateRepository implements PriceRateRepositoryInterface
{
    public function __construct(
        private readonly PriceRateFactory $factory
    ) {}

    public function existsByRoomAndRate(int $roomId, int $rateId): bool
    {
        return DB::table('hotel_price_rate_rooms')
            ->where('room_id', $roomId)
            ->where('rate_id', $rateId)
            ->exists();
    }

    public function get(HotelId $hotelId): array
    {
        $models = PriceRate::whereHotelId($hotelId->value())->get();

        return $this->factory->createCollectionFrom($models);
    }
}
