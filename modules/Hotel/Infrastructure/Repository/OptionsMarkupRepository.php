<?php

declare(strict_types=1);

namespace Module\Hotel\Infrastructure\Repository;

use Module\Hotel\Domain\Repository\OptionsMarkupRepositoryInterface;
use Module\Hotel\Domain\ValueObject\Options\Condition;
use Module\Hotel\Domain\ValueObject\Options\Markup;
use Module\Hotel\Infrastructure\Models\Hotel;
use Module\Shared\Domain\ValueObject\Percent;
use Module\Shared\Domain\ValueObject\TimePeriod;

class OptionsMarkupRepository implements OptionsMarkupRepositoryInterface
{
    public function get(int $hotelId): Markup
    {
        $hotel = Hotel::find($hotelId);

        return array_map(fn(array $condition) => new Condition(
            timePeriod: new TimePeriod($condition['start_time'], $condition['end_time']),
            priceMarkup: new Percent($condition['price_markup']),
        ), $hotel?->markup_settings ?? []);
    }

    public function update(Markup $markup): void
    {
        // TODO: Implement update() method.
    }
}
