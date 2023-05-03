<?php

declare(strict_types=1);

namespace Module\Hotel\Infrastructure\Repository;

use Module\Hotel\Domain\Repository\MarkupRepositoryInterface;
use Module\Hotel\Domain\ValueObject\Options\MarkupSettings;
use Module\Hotel\Infrastructure\Models\Hotel;
use Module\Shared\Domain\Service\JsonSerializer;

class MarkupRepository implements MarkupRepositoryInterface
{
    public function __construct(private readonly JsonSerializer $serializer) {}

    public function get(int $hotelId): MarkupSettings
    {
        $hotel = Hotel::find($hotelId);

        return $this->serializer->deserialize(MarkupSettings::class, $hotel->markup_settings);
    }

    public function update(int $hotelId, MarkupSettings $markup): void
    {
        $hotel = Hotel::find($hotelId);

        $markupSettings = $this->serializer->serialize($markup);

        $hotel->update(['markup_settings' => $markupSettings]);
    }
}
