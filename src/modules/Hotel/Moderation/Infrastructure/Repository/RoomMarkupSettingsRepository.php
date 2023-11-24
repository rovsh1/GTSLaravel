<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Infrastructure\Repository;

use Module\Hotel\Moderation\Domain\Hotel\Entity\Room\RoomMarkups;
use Module\Hotel\Moderation\Domain\Hotel\Repository\RoomMarkupSettingsRepositoryInterface;
use Module\Hotel\Moderation\Infrastructure\Models\Room;

class RoomMarkupSettingsRepository implements RoomMarkupSettingsRepositoryInterface
{
    public function get(int $id): ?RoomMarkups
    {
        $room = Room::find($id);
        if ($room?->markup_settings === null) {
            return null;
        }

        return $this->deserializeSettings($room);
    }

    public function update(RoomMarkups $markupSettings): bool
    {
        $markupSettingsData = json_encode($markupSettings);

        return (bool)Room::whereId($markupSettings->id()->value())->update(['markup_settings' => $markupSettingsData]);
    }

    private function deserializeSettings(Room $room): RoomMarkups
    {
        return RoomMarkups::fromData(json_decode($room->markup_settings, true));
    }
}
