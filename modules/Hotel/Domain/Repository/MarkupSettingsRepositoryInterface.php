<?php

declare(strict_types=1);

namespace Module\Hotel\Domain\Repository;

use Module\Hotel\Domain\ValueObject\MarkupSettings\MarkupSettings;

interface MarkupSettingsRepositoryInterface
{
    /**
     * @param int $hotelId
     * @return MarkupSettings
     */
    public function get(int $hotelId): MarkupSettings;

    public function updateClientMarkups(int $hotelId, ?int $individual, ?int $OTA, ?int $TA, ?int $TO): void;
}
