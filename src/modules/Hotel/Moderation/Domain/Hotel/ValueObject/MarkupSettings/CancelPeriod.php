<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Domain\Hotel\ValueObject\MarkupSettings;

use Carbon\CarbonPeriodImmutable;
use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Contracts\Support\SerializableDataInterface;

final class CancelPeriod implements ValueObjectInterface, SerializableDataInterface
{
    public function __construct(
        private CarbonPeriodImmutable $period,
        private CancelMarkupOption $noCheckInMarkup,
        private readonly DailyMarkupCollection $dailyMarkups
    ) {}

    public function period(): CarbonPeriodImmutable
    {
        return $this->period;
    }

    public function setPeriod(CarbonPeriodImmutable $period): void
    {
        $this->period = $period;
    }

    public function noCheckInMarkup(): CancelMarkupOption
    {
        return $this->noCheckInMarkup;
    }

    public function setNoCheckInMarkup(CancelMarkupOption $noCheckInMarkup): void
    {
        $this->noCheckInMarkup = $noCheckInMarkup;
    }

    public function dailyMarkups(): DailyMarkupCollection
    {
        return $this->dailyMarkups;
    }

    public function toData(): array
    {
        return [
            'period' => $this->period->toIso8601String(),
            'noCheckInMarkup' => $this->noCheckInMarkup->toData(),
            'dailyMarkups' => $this->dailyMarkups->toData(),
        ];
    }

    public static function fromData(array $data): static
    {
        return new static(
            CarbonPeriodImmutable::createFromIso($data['period']),
            CancelMarkupOption::fromData($data['noCheckInMarkup']),
            DailyMarkupCollection::fromData($data['dailyMarkups']),
        );
    }
}
