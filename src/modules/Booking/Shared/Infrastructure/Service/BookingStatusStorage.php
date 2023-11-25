<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Service;

use Module\Booking\Shared\Domain\Booking\Service\BookingStatusStorageInterface;
use Module\Booking\Shared\Infrastructure\Models\StatusSettings;
use Sdk\Shared\Enum\Booking\BookingStatusEnum;

class BookingStatusStorage implements BookingStatusStorageInterface
{
    /**
     * @var array<int, StatusSettings> $statuses
     */
    private static array $statuses;

    public function getName(BookingStatusEnum $status, ?string $locale = null): string
    {
        $statusSettings = $this->statuses()[$status->value];

        return $this->getNameByLocale($statusSettings, $locale);
    }

    public function getColor(BookingStatusEnum $status): ?string
    {
        return $this->statuses()[$status->value]?->color;
    }

    private function getNameByLocale(StatusSettings $model, ?string $locale = null): ?string
    {
        $preparedLocale = $locale ?? 'ru';
        $property = "name_{$preparedLocale}";

        return $model->$property;
    }

    /**
     * @return array<int, StatusSettings>
     */
    public function statuses(): array
    {
        if (!isset(self::$statuses)) {
            return self::$statuses = StatusSettings::onlyBookingStatuses()
                ->get()
                ->keyBy('value')
                ->all();
        }

        return self::$statuses;
    }
}
