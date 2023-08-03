<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Hotel;

use App\Core\Support\Adapters\AbstractHotelAdapter;
use Module\Hotel\Application\UseCase\GetMarkupSettings;
use Module\Hotel\Application\UseCase\GetRoomMarkups;

class MarkupSettingsAdapter extends AbstractHotelAdapter
{
    public function getHotelMarkupSettings(int $hotelId): mixed
    {
        return app(GetMarkupSettings::class)->execute($hotelId);
    }

    public function updateMarkupSettings(int $hotelId, string $key, mixed $value): mixed
    {
        return $this->request('updateMarkupSettingsValue', [
            'hotel_id' => $hotelId,
            'key' => $key,
            'value' => $value,
            'action' => 'update'
        ]);
    }

    public function addMarkupSettingsCondition(int $hotelId, string $key, mixed $value): mixed
    {
        return $this->request('updateMarkupSettingsValue', [
            'hotel_id' => $hotelId,
            'key' => $key,
            'value' => $value,
            'action' => 'addToCollection'
        ]);
    }

    public function deleteMarkupSettingsCondition(int $hotelId, string $key, int $index): mixed
    {
        return $this->request('updateMarkupSettingsValue', [
            'hotel_id' => $hotelId,
            'key' => $key,
            'value' => $index,
            'action' => 'deleteFromCollection'
        ]);
    }

    public function getRoomMarkupSettings(int $hotelId, int $roomId): mixed
    {
        return app(GetRoomMarkups::class)->execute($roomId);
    }

    public function updateRoomMarkupSettings(int $roomId, string $key, int $value): mixed
    {
        return $this->request('updateRoomMarkupSettings', ['room_id' => $roomId, 'key' => $key, 'value' => $value]);
    }
}
