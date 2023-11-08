<?php

namespace Module\Hotel\Moderation\Application\Service\MarkupSettingsSetter;

use Illuminate\Support\Arr;
use Module\Hotel\Moderation\Domain\Hotel\ValueObject\MarkupSettings\CancelPeriodTypeEnum;
use Module\Hotel\Moderation\Domain\Hotel\ValueObject\MarkupSettings\DailyMarkupOption;
use Module\Shared\ValueObject\Percent;

class DailyMarkupOptionBuilder
{
    public function build(array $data): DailyMarkupOption
    {
        if (!Arr::has($data, ['percent', 'cancelPeriodType', 'daysCount'])) {
            throw new \InvalidArgumentException('Can not add daily markup option: Invalid item');
        }

        return new DailyMarkupOption(
            percent: new Percent($data['percent']),
            cancelPeriodType: CancelPeriodTypeEnum::from($data['cancelPeriodType']),
            daysCount: $data['daysCount']
        );
    }
}
