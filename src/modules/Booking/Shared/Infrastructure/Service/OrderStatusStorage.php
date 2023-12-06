<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Service;

use Module\Booking\Shared\Domain\Order\Service\OrderStatusStorageInterface;
use Module\Booking\Shared\Infrastructure\Models\StatusSettings;
use Sdk\Shared\Contracts\Service\TranslatorInterface;
use Sdk\Shared\Enum\Order\OrderStatusEnum;

class OrderStatusStorage implements OrderStatusStorageInterface
{
    /**
     * @var array<int, StatusSettings> $statuses
     */
    private static array $statuses;

    public function __construct(
        private readonly TranslatorInterface $translator
    ) {}

    public function getName(OrderStatusEnum $status, ?string $locale = null): string
    {
        $statusSettings = $this->statuses()[$status->value];

        return $this->getNameByLocale($statusSettings, $locale ?? $this->translator->locale());
    }

    public function getColor(OrderStatusEnum $status): ?string
    {
        return $this->statuses()[$status->value]?->color;
    }

    private function getNameByLocale(StatusSettings $model, string $locale): ?string
    {
        return $model->{"name_$locale"};
    }

    /**
     * @return array<int, StatusSettings>
     */
    private function statuses(): array
    {
        if (!isset(self::$statuses)) {
            return self::$statuses = StatusSettings::onlyOrderStatuses()
                ->get()
                ->keyBy('status')
                ->all();
        }

        return self::$statuses;
    }
}
