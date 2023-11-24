<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Shared\ValueObject;

use Carbon\CarbonImmutable;
use Module\Booking\Shared\Domain\Shared\ValueObject\CancelCondition\CancelFeeValue;
use Module\Booking\Shared\Domain\Shared\ValueObject\CancelCondition\DailyCancelFeeValueCollection;
use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Contracts\Support\SerializableDataInterface;

class CancelConditions implements ValueObjectInterface, SerializableDataInterface
{
    public function __construct(
        private readonly CancelFeeValue $noCheckInMarkup,
        private readonly DailyCancelFeeValueCollection $dailyMarkups,
        private readonly ?CarbonImmutable $cancelNoFeeDate = null,
    ) {}

    public function cancelNoFeeDate(): ?CarbonImmutable
    {
        return $this->cancelNoFeeDate;
    }

    public function noCheckInMarkup(): CancelFeeValue
    {
        return $this->noCheckInMarkup;
    }

    public function dailyMarkups(): DailyCancelFeeValueCollection
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
            CancelFeeValue::fromData($data['noCheckInMarkup']),
            DailyCancelFeeValueCollection::fromData($data['dailyMarkups']),
            $cancelNoFeeDate !== null ? CarbonImmutable::createFromTimestamp($cancelNoFeeDate) : null,
        );
    }
}
