<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Hotel;

use App\Shared\Support\Adapters\AbstractHotelAdapter;
use Module\Catalog\Application\Admin\Response\HotelDto;
use Module\Catalog\Application\Admin\UseCase\FindHotelById;
use Module\Catalog\Application\Admin\UseCase\UpdateTimeSettings;

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
