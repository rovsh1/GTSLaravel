<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Shared\ValueObject;

use Carbon\CarbonImmutable;
use Module\Booking\Shared\Domain\Shared\ValueObject\CancelCondition\CancelFeeValue;
use Module\Booking\Shared\Domain\Shared\ValueObject\CancelCondition\DailyCancelFeeValueCollection;
use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Contracts\Support\SerializableInterface;

class CancelConditions implements ValueObjectInterface, SerializableInterface
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

    public function serialize(): array
    {
        return [
            'cancelNoFeeDate' => $this->cancelNoFeeDate?->getTimestamp(),
            'noCheckInMarkup' => $this->noCheckInMarkup->serialize(),
            'dailyMarkups' => $this->dailyMarkups->toData()
        ];
    }

    public static function deserialize(array $payload): static
    {
        $cancelNoFeeDate = $payload['cancelNoFeeDate'] ?? null;

        return new static(
            CancelFeeValue::deserialize($payload['noCheckInMarkup']),
            DailyCancelFeeValueCollection::fromData($payload['dailyMarkups']),
            $cancelNoFeeDate !== null ? CarbonImmutable::createFromTimestamp($cancelNoFeeDate) : null,
        );
    }
}
