<?php

declare(strict_types=1);

namespace Module\Hotel\Pricing\Infrastructure\Repository;

use DateTimeInterface;
use Module\Hotel\Pricing\Domain\Hotel\Hotel;
use Module\Hotel\Pricing\Domain\Hotel\Repository\HotelRepositoryInterface;
use Module\Hotel\Pricing\Domain\Hotel\ValueObject\HotelId;
use Module\Hotel\Pricing\Domain\Hotel\ValueObject\RoomId;
use Module\Hotel\Pricing\Domain\Hotel\ValueObject\SeasonId;
use Module\Hotel\Pricing\Infrastructure\Models\Hotel as Model;
use Module\Hotel\Pricing\Infrastructure\Models\Season;
use Sdk\Shared\ValueObject\Percent;

class HotelRepository implements HotelRepositoryInterface
{
    private static array $cached = [];

    public function findByRoomId(RoomId $roomId): ?Hotel
    {
        if (isset(self::$cached[$roomId->value()])) {
            return self::$cached[$roomId->value()];
        }

        $model = Model::findByRoomId($roomId->value());

        return self::$cached[$roomId->value()] = $this->fromModel($model);
    }

    public function findActiveSeasonId(HotelId $hotelId, DateTimeInterface $date): ?SeasonId
    {
        $model = Season::query()
            ->whereHotelId($hotelId->value())
            ->whereDateIncluded($date)
            ->first();

        return $model ? new SeasonId($model->id) : null;
    }

    public function findOrFail(HotelId $hotelId): Hotel
    {
        $model = Model::find($hotelId->value());

        return $this->fromModel($model);
    }

    private function fromModel($model): Hotel
    {
        return new Hotel(
            id: new HotelId($model->id),
            currency: $model->currency,
            vat: new Percent($model->markup_settings['vat']),
            touristTax: new Percent($model->markup_settings['touristTax'])
        );
    }
}
