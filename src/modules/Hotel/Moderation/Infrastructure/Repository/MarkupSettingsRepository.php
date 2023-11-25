<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Infrastructure\Repository;

use Module\Hotel\Moderation\Domain\Hotel\Entity\MarkupSettings;
use Module\Hotel\Moderation\Domain\Hotel\Repository\MarkupSettingsRepositoryInterface;
use Module\Hotel\Moderation\Infrastructure\Models\Hotel;

class MarkupSettingsRepository implements MarkupSettingsRepositoryInterface
{
    public function get(int $id): MarkupSettings
    {
        $hotel = Hotel::find($id);

        return $this->deserializeSettings($hotel);
    }

    public function update(MarkupSettings $markupSettings): bool
    {
        $markupSettingsData = json_encode($markupSettings);

        return (bool)Hotel::whereId($markupSettings->id()->value())->update(['markup_settings' => $markupSettingsData]);
    }

    private function deserializeSettings(Hotel $hotel): MarkupSettings
    {
        return MarkupSettings::deserialize(json_decode($hotel->markup_settings, true));
    }
}
