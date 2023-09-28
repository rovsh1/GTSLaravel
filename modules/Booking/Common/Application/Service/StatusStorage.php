<?php

declare(strict_types=1);

namespace Module\Booking\Common\Application\Service;

use Module\Booking\Common\Application\Response\StatusDto;
use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;
use Module\Booking\Common\Infrastructure\Models\StatusSettings;

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
        $preparedLocale = $locale !== null ? $locale : 'ru';
        $property = "name_{$preparedLocale}";

        return $model->$property;
    }
}