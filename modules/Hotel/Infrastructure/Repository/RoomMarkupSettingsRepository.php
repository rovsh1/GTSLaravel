<?php

declare(strict_types=1);

namespace Module\Hotel\Infrastructure\Repository;

use Module\Hotel\Domain\Entity\Room\MarkupSettings;
use Module\Hotel\Domain\Repository\RoomMarkupSettingsRepositoryInterface;
use Module\Hotel\Infrastructure\Models\Room;
use Module\Shared\Domain\Service\SerializerInterface;

class RoomMarkupSettingsRepository implements RoomMarkupSettingsRepositoryInterface
{
    public function __construct(
        private readonly SerializerInterface $serializer
    ) {}

    public function get(int $id): MarkupSettings
    {
        $room = Room::find($id);

        return $this->deserializeSettings($room);
    }

    public function update(MarkupSettings $markupSettings): bool
    {
        $markupSettingsData = $this->serializer->serialize($markupSettings);

        return (bool)Room::whereId($markupSettings->id()->value())->update(['markup_settings' => $markupSettingsData]);
    }

    private function deserializeSettings(Room $room): MarkupSettings
    {
        return $this->serializer->deserialize(MarkupSettings::class, $room->markup_settings);
    }
}