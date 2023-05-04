<?php

declare(strict_types=1);

namespace Module\Hotel\Domain\ValueObject\MarkupSettings;

use Carbon\CarbonPeriod;
use Module\Shared\Domain\ValueObject\SerializableDataInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class CancelPeriod implements ValueObjectInterface, SerializableDataInterface
{
    public function __construct(
        private readonly CarbonPeriod $period,
        private readonly CancelMarkupOption $noCheckInMarkup,
        private readonly DailyMarkupCollection $dailyMarkups
    ) {}

    public function period(): CarbonPeriod
    {
        return $this->period;
    }

    public function noCheckInMarkup(): CancelMarkupOption
    {
        return $this->noCheckInMarkup;
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
            new CarbonPeriod($data['period']),
            CancelMarkupOption::fromData($data['noCheckInMarkup']),
            DailyMarkupCollection::fromData($data['dailyMarkups']),
        );
    }
}
