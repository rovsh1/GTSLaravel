<?php

declare(strict_types=1);

namespace Module\Hotel\Infrastructure\Repository;

use Module\Hotel\Domain\Entity\Room\MarkupSettings;
use Module\Hotel\Domain\Repository\RoomMarkupSettingsRepositoryInterface;
use Module\Hotel\Infrastructure\Models\Room;
use Module\Shared\Infrastructure\Service\JsonSerializer;

class RoomMarkupSettingsRepository implements RoomMarkupSettingsRepositoryInterface
{
    public function __construct(
        private readonly JsonSerializer $serializer
    ) {}

    public function get(int $id): MarkupSettings
    {
        $hotel = Room::find($id);

        return $this->deserializeSettings($hotel);
    }

    public function update(MarkupSettings $markupSettings): bool
    {
        $markupSettingsData = $this->serializer->serialize($markupSettings);

        return (bool)Room::whereId($markupSettings->id())->update(['markup_settings' => $markupSettingsData]);
    }

    private function deserializeSettings(Room $room): MarkupSettings
    {
        return $this->serializer->deserialize(MarkupSettings::class, $room->markup_settings);
    }
}
