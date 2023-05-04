<?php

declare(strict_types=1);

namespace Module\Hotel\Infrastructure\Repository;

use Module\Hotel\Domain\Repository\MarkupSettingsRepositoryInterface;
use Module\Hotel\Domain\ValueObject\MarkupSettings\MarkupSettings;
use Module\Hotel\Infrastructure\Models\Hotel;
use Module\Shared\Domain\Service\JsonSerializer;
use Module\Shared\Domain\ValueObject\Percent;

class MarkupSettingsRepository implements MarkupSettingsRepositoryInterface
{
    public function __construct(private readonly JsonSerializer $serializer) {}

    public function get(int $hotelId): MarkupSettings
    {
        $hotel = Hotel::find($hotelId);

        return $this->serializer->deserialize(MarkupSettings::class, $hotel->markup_settings);
    }

    public function updateClientMarkups(int $hotelId, ?int $individual, ?int $OTA, ?int $TA, ?int $TO): void
    {
        $hotel = Hotel::find($hotelId);

        /** @var MarkupSettings $markup */
        $markup = $this->serializer->deserialize(MarkupSettings::class, $hotel->markup_settings);

        if ($individual !== null) {
            $markup->clientMarkups()->setIndividual(new Percent($individual));
        }
        if ($OTA !== null) {
            $markup->clientMarkups()->setOTA(new Percent($OTA));
        }
        if ($TA !== null) {
            $markup->clientMarkups()->setTA(new Percent($TA));
        }
        if ($TO !== null) {
            $markup->clientMarkups()->setTO(new Percent($TO));
        }
        $markupSettings = $this->serializer->serialize($markup);

        $hotel->update(['markup_settings' => $markupSettings]);
    }
}
