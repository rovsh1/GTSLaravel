<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Infrastructure\Repository;

use Module\Hotel\Moderation\Domain\Hotel\Entity\Room\RoomMarkups;
use Module\Hotel\Moderation\Domain\Hotel\Repository\RoomMarkupSettingsRepositoryInterface;
use Module\Hotel\Moderation\Infrastructure\Models\Room;
use Module\Shared\Contracts\Service\SerializerInterface;

class RoomMarkupSettingsRepository implements RoomMarkupSettingsRepositoryInterface
{
    public function __construct(
        private readonly SerializerInterface $serializer
    ) {}

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
        $markupSettingsData = $this->serializer->serialize($markupSettings);

        return (bool)Room::whereId($markupSettings->id()->value())->update(['markup_settings' => $markupSettingsData]);
    }

    private function deserializeSettings(Room $room): RoomMarkups
    {
        return $this->serializer->deserialize(RoomMarkups::class, $room->markup_settings);
    }
}