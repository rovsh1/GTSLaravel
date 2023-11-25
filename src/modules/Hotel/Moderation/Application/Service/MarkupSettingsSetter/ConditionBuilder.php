<?php

namespace Module\Hotel\Moderation\Application\Service\MarkupSettingsSetter;

use Illuminate\Support\Arr;
use Module\Hotel\Moderation\Domain\Hotel\ValueObject\MarkupSettings\Condition;
use Sdk\Shared\ValueObject\Percent;
use Sdk\Shared\ValueObject\TimePeriod;

class ConditionBuilder
{
    public function build(array $data): Condition
    {
        if (!Arr::has($data, ['from', 'to', 'percent'])) {
            throw new \InvalidArgumentException('Can not add condition: Invalid item');
        }

        return new Condition(
            timePeriod: new TimePeriod($data['from'], $data['to']),
            priceMarkup: new Percent($data['percent'])
        );
    }
}
