<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Application\Factory;

use Module\Hotel\Moderation\Application\Dto\MarkupSettings\CancelMarkupOptionDto;
use Module\Hotel\Moderation\Application\Dto\MarkupSettings\CancelPeriodDto;
use Module\Hotel\Moderation\Application\Dto\MarkupSettings\ConditionDto;
use Module\Hotel\Moderation\Application\Dto\MarkupSettings\DailyMarkupDto;
use Module\Hotel\Moderation\Application\Dto\MarkupSettingsDto;
use Module\Hotel\Moderation\Domain\Hotel\Entity\MarkupSettings;
use Module\Hotel\Moderation\Domain\Hotel\ValueObject\MarkupSettings\CancelPeriod;
use Module\Hotel\Moderation\Domain\Hotel\ValueObject\MarkupSettings\Condition;
use Module\Hotel\Moderation\Domain\Hotel\ValueObject\MarkupSettings\DailyMarkupOption;

class MarkupSettingsDtoFactory
{
    public function from(MarkupSettings $entity): MarkupSettingsDto
    {
        return new MarkupSettingsDto(
            vat: $entity->vat()->value(),
            touristTax: $entity->touristTax()->value(),
            earlyCheckIn: $this->buildConditionDtos($entity->earlyCheckIn()->toArray()),
            lateCheckOut: $this->buildConditionDtos($entity->lateCheckOut()->toArray()),
            cancelPeriods: $this->buildCancelPeriodDtos($entity->cancelPeriods()->toArray()),
        );
    }

    /**
     * @param Condition[] $conditions
     * @return ConditionDto[]
     */
    private function buildConditionDtos(array $conditions): array
    {
        return array_map(fn(Condition $r) => new ConditionDto(
            from: $r->timePeriod()->from(),
            to: $r->timePeriod()->to(),
            percent: $r->priceMarkup()->value()
        ), $conditions);
    }

    /**
     * @param CancelPeriod[] $periods
     * @return CancelPeriodDto[]
     */
    private function buildCancelPeriodDtos(array $periods): array
    {
        return array_map(fn(CancelPeriod $entity) => new CancelPeriodDto(
            from: $entity->period()->getStartDate(),
            to: $entity->period()->getEndDate(),
            noCheckInMarkup: new CancelMarkupOptionDto(
                percent: $entity->noCheckInMarkup()->percent()->value(),
                cancelPeriodType: $entity->noCheckInMarkup()->cancelPeriodType()->value
            ),
            dailyMarkups: $this->buildDailyMarkupDtos($entity->dailyMarkups()->toArray())
        ), $periods);
    }

    /**
     * @param DailyMarkupOption[] $markups
     * @return DailyMarkupDto[]
     */
    private function buildDailyMarkupDtos(array $markups): array
    {
        return array_map(fn($r) => new DailyMarkupDto(
            percent: $r->percent()->value(),
            cancelPeriodType: $r->cancelPeriodType()->value,
            daysCount: $r->daysCount()
        ), $markups);
    }
}
