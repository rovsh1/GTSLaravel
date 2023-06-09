<?php

declare(strict_types=1);

namespace Module\Booking\Common\Application\Query\Admin;

use Module\Booking\Common\Application\Dto\StatusDto;
use Module\Booking\Common\Infrastructure\Models\StatusSettings;
use Sdk\Module\Contracts\Bus\QueryHandlerInterface;
use Sdk\Module\Contracts\Bus\QueryInterface;

class GetStatusSettingsHandler implements QueryHandlerInterface
{
    /**
     * @param QueryInterface $query
     * @return StatusDto[]
     */
    public function handle(QueryInterface $query): array
    {
        return StatusSettings::get()->map(
            fn(StatusSettings $settings) => new StatusDto(
                $settings->value,
                $this->getNameByLocale($settings),
                $settings->color,
            )
        )->all();
    }

    private function getNameByLocale(StatusSettings $model, ?string $locale = null): ?string
    {
        $preparedLocale = $locale !== null ? $locale : 'ru';
        $property = "name_{$preparedLocale}";

        return $model->$property;
    }
}
