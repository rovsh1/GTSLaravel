<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Shared\ValueObject;

use Carbon\CarbonImmutable;
use Module\Booking\Domain\Shared\ValueObject\CancelCondition\CancelMarkupOption;
use Module\Booking\Domain\Shared\ValueObject\CancelCondition\DailyMarkupCollection;
use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Contracts\Support\SerializableDataInterface;

class CancelConditions implements ValueObjectInterface, SerializableDataInterface
{
    public function __construct(
        private readonly CancelMarkupOption $noCheckInMarkup,
        private readonly DailyMarkupCollection $dailyMarkups,
        private readonly ?CarbonImmutable $cancelNoFeeDate = null,
    ) {}

    public function cancelNoFeeDate(): ?CarbonImmutable
    {
        return $this->cancelNoFeeDate;
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
            'cancelNoFeeDate' => $this->cancelNoFeeDate?->getTimestamp(),
            'noCheckInMarkup' => $this->noCheckInMarkup->toData(),
            'dailyMarkups' => $this->dailyMarkups->toData()
        ];
    }

    public static function fromData(array $data): static
    {
        $cancelNoFeeDate = $data['cancelNoFeeDate'] ?? null;

        return new static(
            CancelMarkupOption::fromData($data['noCheckInMarkup']),
            DailyMarkupCollection::fromData($data['dailyMarkups']),
            $cancelNoFeeDate !== null ? CarbonImmutable::createFromTimestamp($cancelNoFeeDate) : null,
        );
    }
}
