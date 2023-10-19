<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Booking\Service;

use Module\Booking\Domain\Shared\ValueObject\BookingStatusEnum;
use Module\Booking\Infrastructure\Shared\Models\StatusSettings;

class StatusTranslator
{
    public function getName(BookingStatusEnum $status): string
    {
        $statusSettings = StatusSettings::whereValue($status)->first();

        return $this->getNameByLocale($statusSettings);
    }

    private function getNameByLocale(StatusSettings $model, ?string $locale = null): ?string
    {
        $preparedLocale = $locale ?? 'ru';
        $property = "name_{$preparedLocale}";

        return $model->$property;
    }
}
