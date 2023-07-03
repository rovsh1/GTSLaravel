<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Hotel;

use App\Core\Support\Adapters\AbstractHotelAdapter;
use Module\Hotel\Application\Response\HotelDto;
use Module\Hotel\Application\UseCase\FindHotelById;
use Module\Hotel\Application\UseCase\UpdateTimeSettings;

class SettingsAdapter extends AbstractHotelAdapter
{
    public function getHotelSettings(int $hotelId): ?HotelDto
    {
        return app(FindHotelById::class)->execute($hotelId);
    }

    public function updateHotelTimeSettings(
        int $hotelId,
        string $checkInAfter,
        string $checkOutBefore,
        ?string $breakfastPeriodFrom,
        ?string $breakfastPeriodTo
    ): void {
        app(UpdateTimeSettings::class)->execute(
            $hotelId,
            $checkInAfter,
            $checkOutBefore,
            $breakfastPeriodFrom,
            $breakfastPeriodTo
        );
    }
}
