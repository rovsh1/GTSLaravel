<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Hotel;

use App\Core\Support\Adapters\AbstractHotelAdapter;

class MarkupSettingsAdapter extends AbstractHotelAdapter
{
    public function getHotelMarkupSettings(int $hotelId): mixed
    {
        return $this->request('getHotelMarkupSettings', ['hotel_id' => $hotelId]);
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
}
