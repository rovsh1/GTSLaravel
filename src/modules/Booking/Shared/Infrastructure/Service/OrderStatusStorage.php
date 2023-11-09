<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Service;

use Module\Booking\Shared\Domain\Order\Service\OrderStatusStorageInterface;
use Module\Booking\Shared\Infrastructure\Models\StatusSettings;
use Module\Shared\Enum\Order\OrderStatusEnum;

class OrderStatusStorage implements OrderStatusStorageInterface
{
    /**
     * @var array<int, StatusSettings> $statuses
     */
    private static array $statuses;

    public function getName(OrderStatusEnum $status, ?string $locale = null): string
    {
        $statusSettings = $this->statuses()[$status->value];

        return $this->getNameByLocale($statusSettings, $locale);
    }

    public function getColor(OrderStatusEnum $status): ?string
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
            return self::$statuses = StatusSettings::onlyOrderStatuses()
                ->get()
                ->keyBy('value')
                ->all();
        }

        return self::$statuses;
    }
}
