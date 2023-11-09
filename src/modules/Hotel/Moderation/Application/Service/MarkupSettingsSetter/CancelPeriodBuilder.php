<?php

namespace Module\Hotel\Moderation\Application\Service\MarkupSettingsSetter;

use Carbon\CarbonPeriodImmutable;
use Illuminate\Support\Arr;
use Module\Hotel\Moderation\Domain\Hotel\ValueObject\MarkupSettings\CancelMarkupOption;
use Module\Hotel\Moderation\Domain\Hotel\ValueObject\MarkupSettings\CancelPeriod;
use Module\Hotel\Moderation\Domain\Hotel\ValueObject\MarkupSettings\CancelPeriodTypeEnum;
use Module\Hotel\Moderation\Domain\Hotel\ValueObject\MarkupSettings\DailyMarkupCollection;
use Module\Shared\ValueObject\Percent;

class CancelPeriodBuilder
{
    public function build(array $data): CancelPeriod
    {
        if (!Arr::has($data, ['from', 'to', 'noCheckInMarkup', 'dailyMarkups'])) {
            throw new \InvalidArgumentException('Can not add cancel period: Invalid item');
        }
        if (!Arr::has($data['noCheckInMarkup'], ['percent', 'cancelPeriodType'])) {
            throw new \InvalidArgumentException('Can not add condition: Invalid noCheckInMarkup item');
        }

        $dailyBuilder = new DailyMarkupOptionBuilder();

        return new CancelPeriod(
            period: new CarbonPeriodImmutable($data['from'], $data['to']),
            noCheckInMarkup: new CancelMarkupOption(
                new Percent($data['noCheckInMarkup']['percent']),
                CancelPeriodTypeEnum::from($data['noCheckInMarkup']['cancelPeriodType'])
            ),
            dailyMarkups: new DailyMarkupCollection(
                array_map(fn(array $item) => $dailyBuilder->build($item), $data['dailyMarkups'])
            )
        );
    }
}
