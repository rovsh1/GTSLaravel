<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Hotel;

use Module\Hotel\Moderation\Application\Dto\HotelDto;
use Module\Hotel\Moderation\Application\UseCase\FindHotelById;
use Module\Hotel\Moderation\Application\UseCase\UpdateTimeSettings;

class SettingsAdapter
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
