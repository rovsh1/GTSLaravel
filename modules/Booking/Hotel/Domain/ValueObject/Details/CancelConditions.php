<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Domain\ValueObject\Details;

use Carbon\CarbonImmutable;
use Module\Booking\Hotel\Domain\ValueObject\Details\CancelCondition\CancelMarkupOption;
use Module\Booking\Hotel\Domain\ValueObject\Details\CancelCondition\DailyMarkupCollection;
use Module\Shared\Domain\ValueObject\SerializableDataInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

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
            $cancelNoFeeDate !== null ? CarbonImmutable::createFromFormat('U', $cancelNoFeeDate) : null,
        );
    }
}
