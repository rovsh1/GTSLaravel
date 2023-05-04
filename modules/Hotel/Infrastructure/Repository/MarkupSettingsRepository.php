<?php

declare(strict_types=1);

namespace Module\Hotel\Infrastructure\Repository;

use Module\Hotel\Domain\Repository\MarkupSettingsRepositoryInterface;
use Module\Hotel\Domain\ValueObject\MarkupSettings\MarkupSettings;
use Module\Hotel\Infrastructure\Models\Hotel;
use Module\Shared\Domain\Service\DomainSerializerInterface;

class MarkupSettingsRepository implements MarkupSettingsRepositoryInterface
{
    public function __construct(
        private readonly DomainSerializerInterface $serializer
    ) {}

    public function get(int $hotelId): MarkupSettings
    {
        $hotel = Hotel::find($hotelId);

        return $this->deserializeSettings($hotel);
    }

    public function updateClientMarkups(int $hotelId, MarkupSettings $markupSettings): bool
    {
        $hotel = Hotel::find($hotelId);

        $markupSettingsData = $this->serializer->serialize($markupSettings);

        return $hotel->update(['markup_settings' => $markupSettingsData]);
    }

    private function deserializeSettings(Hotel $hotel): MarkupSettings
    {
        return $this->serializer->deserialize(MarkupSettings::class, $hotel->markup_settings);
    }
}
