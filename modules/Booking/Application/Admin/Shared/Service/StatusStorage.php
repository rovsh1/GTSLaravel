<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\Shared\Service;

use Module\Booking\Application\Admin\Shared\Response\StatusDto;
use Module\Booking\Domain\Shared\ValueObject\BookingStatusEnum;
use Module\Booking\Infrastructure\Shared\Models\StatusSettings;

class StatusStorage
{
    /**
     * @var array<int, StatusDto> $statuses
     */
    private static array $statuses;

    public function get(BookingStatusEnum $status): StatusDto
    {
        return $this->statuses()[$status->value];
    }

    public function statuses(): array
    {
        if (isset(self::$statuses)) {
            return self::$statuses;
        }

        return self::$statuses = StatusSettings::get()->map(
            fn(StatusSettings $settings) => new StatusDto(
                $settings->value,
                $this->getNameByLocale($settings),
                $settings->color,
            )
        )->keyBy('id')->all();
    }

    private function getNameByLocale(StatusSettings $model, ?string $locale = null): ?string
    {
        $preparedLocale = $locale ?? 'ru';
        $property = "name_{$preparedLocale}";

        return $model->$property;
    }
}
