<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Domain\Order\Service\VoucherGenerator\Factory;

use Module\Booking\Moderation\Domain\Order\Service\VoucherGenerator\Dto\Service\CancelConditionsDto;
use Module\Booking\Moderation\Domain\Order\Service\VoucherGenerator\Dto\Service\DailyCancelFeeValueDto;
use Module\Hotel\Moderation\Domain\Hotel\ValueObject\MarkupSettings\CancelPeriodTypeEnum;
use Sdk\Booking\ValueObject\CancelCondition\DailyCancelFeeValue;
use Sdk\Booking\ValueObject\CancelConditions;

class CancelConditionsDataFactory
{
    public function build(CancelConditions $cancelConditions): ?CancelConditionsDto
    {
        $noCheckInMarkup = $cancelConditions->noCheckInMarkup();

        $dailyMarkups = $cancelConditions->dailyMarkups()->map(fn(DailyCancelFeeValue $dailyCancelFeeValue) => new DailyCancelFeeValueDto(
            $dailyCancelFeeValue->value()->value(),
            $dailyCancelFeeValue->value()->type(),
            $dailyCancelFeeValue->daysCount(),
            $this->getHumanCancelPeriodType($dailyCancelFeeValue->cancelPeriodType()->value),
        ));

        return new CancelConditionsDto(
            noCheckInMarkup: $noCheckInMarkup->value()->value(),
            noCheckInMarkupType: $this->getHumanCancelPeriodType($noCheckInMarkup->cancelPeriodType()->value),
            dailyMarkups: $dailyMarkups,
            cancelNoFeeDate: $cancelConditions->cancelNoFeeDate()?->toDateTimeImmutable()
        );
    }

    private function getHumanCancelPeriodType(int $cancelPeriodType): string
    {
        return $cancelPeriodType === CancelPeriodTypeEnum::FULL_PERIOD->value ? __('За весь период') : __('За первую ночь');
    }
}
