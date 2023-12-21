<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Infrastructure\Repository;

use Module\Hotel\Moderation\Domain\Hotel\Entity\MarkupSettings;
use Module\Hotel\Moderation\Domain\Hotel\Repository\MarkupSettingsRepositoryInterface;
use Module\Hotel\Moderation\Domain\Hotel\ValueObject\HotelId;
use Module\Hotel\Moderation\Domain\Hotel\ValueObject\MarkupSettings\CancelPeriodCollection;
use Module\Hotel\Moderation\Domain\Hotel\ValueObject\MarkupSettings\EarlyCheckInCollection;
use Module\Hotel\Moderation\Domain\Hotel\ValueObject\MarkupSettings\LateCheckOutCollection;
use Module\Hotel\Moderation\Infrastructure\Models\Hotel;
use Sdk\Shared\ValueObject\Percent;

class MarkupSettingsRepository implements MarkupSettingsRepositoryInterface
{
    public function get(int $id): MarkupSettings
    {
        $hotel = Hotel::find($id);

        return $this->deserializeSettings($hotel);
    }

    public function update(MarkupSettings $markupSettings): bool
    {
        $markupSettingsData = $markupSettings->serialize();

        return (bool)Hotel::whereId($markupSettings->id()->value())->update(['markup_settings' => $markupSettingsData]);
    }

    private function deserializeSettings(Hotel $hotel): MarkupSettings
    {
        if (empty($hotel->markup_settings)) {
            return $this->getDefaultMarkupSettings($hotel->id);
        }

        return MarkupSettings::deserialize(json_decode($hotel->markup_settings, true));
    }

    private function getDefaultMarkupSettings(int $hotelId): MarkupSettings
    {
        return new MarkupSettings(
            new HotelId($hotelId),
            new Percent(0),
            new Percent(0),
            new EarlyCheckInCollection(),
            new LateCheckOutCollection(),
            new CancelPeriodCollection()
        );
    }
}
