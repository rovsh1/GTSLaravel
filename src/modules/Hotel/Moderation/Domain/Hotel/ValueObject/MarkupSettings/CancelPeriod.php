<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Domain\Hotel\ValueObject\MarkupSettings;

use Carbon\CarbonPeriodImmutable;
use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Contracts\Support\SerializableInterface;

final class CancelPeriod implements ValueObjectInterface, SerializableInterface
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

    public function serialize(): array
    {
        return [
            'period' => $this->period->toIso8601String(),
            'noCheckInMarkup' => $this->noCheckInMarkup->serialize(),
            'dailyMarkups' => $this->dailyMarkups->serialize(),
        ];
    }

    public static function deserialize(array $payload): static
    {
        return new static(
            CarbonPeriodImmutable::createFromIso($payload['period']),
            CancelMarkupOption::deserialize($payload['noCheckInMarkup']),
            DailyMarkupCollection::deserialize($payload['dailyMarkups']),
        );
    }
}
