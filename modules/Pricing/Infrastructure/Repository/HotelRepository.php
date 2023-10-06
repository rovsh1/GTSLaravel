<?php

declare(strict_types=1);

namespace Module\Pricing\Infrastructure\Repository;

use DateTimeInterface;
use Module\Hotel\Infrastructure\Models\Season;
use Module\Hotel\Infrastructure\Models\Hotel as Model;
use Module\Pricing\Domain\Hotel\Hotel;
use Module\Pricing\Domain\Hotel\Repository\HotelRepositoryInterface;
use Module\Pricing\Domain\Hotel\ValueObject\HotelId;
use Module\Pricing\Domain\Hotel\ValueObject\RoomId;
use Module\Pricing\Domain\Hotel\ValueObject\SeasonId;
use Module\Shared\Domain\ValueObject\Percent;

class HotelRepository implements HotelRepositoryInterface
{
    private static array $cached = [];

    public function findByRoomId(RoomId $roomId): ?Hotel
    {
        if (isset(self::$cached[$roomId->value()])) {
            return self::$cached[$roomId->value()];
        }

        $model = Model::findByRoomId($roomId);

        return self::$cached[$roomId->value()] = new Hotel(
            id: new HotelId($model->id),
            currency: $model->currency,
            vat: new Percent(0),
            touristTax: new Percent(0)
        );
    }

    public function findActiveSeasonId(HotelId $hotelId, DateTimeInterface $date): ?SeasonId
    {
        $model = Season::query()
            ->whereHotelId($hotelId)
            ->where('hotel_seasons.date_start', '<=', $date->format('Y-m-d 00:00:00'))
            ->where('hotel_seasons.date_end', '>=', $date->format('Y-m-d 23:59:59'))
            ->first();

        return $model ? new SeasonId($model->id) : null;
    }
}
