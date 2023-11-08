<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Hotel;

use Module\Hotel\Moderation\Application\Admin\Enums\UpdateMarkupSettingsActionEnum;
use Module\Hotel\Moderation\Application\Admin\Response\MarkupSettingsDto;
use Module\Hotel\Moderation\Application\Admin\Response\RoomMarkupsDto;
use Module\Hotel\Moderation\Application\Admin\UseCase\GetMarkupSettings;
use Module\Hotel\Moderation\Application\Admin\UseCase\GetRoomMarkups;
use Module\Hotel\Moderation\Application\Admin\UseCase\MarkupSettings\UpdateMarkupSettingsValue;
use Module\Hotel\Moderation\Application\Admin\UseCase\MarkupSettings\UpdateRoomMarkupSettings;

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

    public function getRoomMarkupSettings(int $hotelId, int $roomId): ?RoomMarkupsDto
    {
        return app(GetRoomMarkups::class)->execute($roomId);
    }

    public function updateRoomMarkupSettings(int $roomId, string $key, int $value): void
    {
        app(UpdateRoomMarkupSettings::class)->execute($roomId, $key, $value);
    }
}
