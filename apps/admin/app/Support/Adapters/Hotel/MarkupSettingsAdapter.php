<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Hotel;

use Module\Hotel\Moderation\Application\Enums\UpdateMarkupSettingsActionEnum;
use Module\Hotel\Moderation\Application\Response\MarkupSettingsDto;
use Module\Hotel\Moderation\Application\UseCase\GetMarkupSettings;
use Module\Hotel\Moderation\Application\UseCase\MarkupSettings\UpdateMarkupSettingsValue;

class MarkupSettingsAdapter
{
    public function getHotelMarkupSettings(int $hotelId): MarkupSettingsDto
    {
        return app(GetMarkupSettings::class)->execute($hotelId);
    }

    public function updateMarkupSettings(int $hotelId, string $key, mixed $value): void
    {
        app(UpdateMarkupSettingsValue::class)->execute(
            $hotelId,
            $key,
            $value,
            UpdateMarkupSettingsActionEnum::UPDATE
        );
    }

    public function addMarkupSettingsCondition(int $hotelId, string $key, mixed $value): void
    {
        app(UpdateMarkupSettingsValue::class)->execute(
            $hotelId,
            $key,
            $value,
            UpdateMarkupSettingsActionEnum::ADD_TO_COLLECTION
        );
    }

    public function deleteMarkupSettingsCondition(int $hotelId, string $key, int $index): void
    {
        app(UpdateMarkupSettingsValue::class)->execute(
            $hotelId,
            $key,
            $index,
            UpdateMarkupSettingsActionEnum::DELETE_FROM_COLLECTION
        );
    }
}
