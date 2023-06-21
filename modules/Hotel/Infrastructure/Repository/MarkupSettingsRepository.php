<?php

declare(strict_types=1);

namespace Module\Hotel\Infrastructure\Repository;

use Module\Hotel\Domain\Entity\MarkupSettings;
use Module\Hotel\Domain\Repository\MarkupSettingsRepositoryInterface;
use Module\Hotel\Infrastructure\Models\Hotel;
use Module\Shared\Domain\Service\SerializerInterface;

class MarkupSettingsRepository implements MarkupSettingsRepositoryInterface
{
    public function __construct(
        private readonly SerializerInterface $serializer
    ) {}

    public function get(int $id): MarkupSettings
    {
        $hotel = Hotel::find($id);

        return $this->deserializeSettings($hotel);
    }

    public function update(MarkupSettings $markupSettings): bool
    {
        $markupSettingsData = $this->serializer->serialize($markupSettings);

        return (bool)Hotel::whereId($markupSettings->id()->value())->update(['markup_settings' => $markupSettingsData]);
    }

    private function deserializeSettings(Hotel $hotel): MarkupSettings
    {
        return $this->serializer->deserialize(MarkupSettings::class, $hotel->markup_settings);
    }
}
