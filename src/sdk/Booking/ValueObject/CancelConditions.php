<?php

declare(strict_types=1);

namespace Sdk\Booking\ValueObject;

use Carbon\CarbonImmutable;
use Sdk\Booking\ValueObject\CancelCondition\CancelFeeValue;
use Sdk\Booking\ValueObject\CancelCondition\DailyCancelFeeValueCollection;
use Sdk\Module\Support\DateTimeImmutable;
use Sdk\Shared\Contracts\Support\SerializableInterface;

class CancelConditions implements SerializableInterface
{
    public function __construct(
        private readonly CancelFeeValue $noCheckInMarkup,
        private readonly DailyCancelFeeValueCollection $dailyMarkups,
        private readonly ?DateTimeImmutable $cancelNoFeeDate = null,
    ) {}

    public function cancelNoFeeDate(): ?DateTimeImmutable
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
            $cancelNoFeeDate !== null ? DateTimeImmutable::createFromTimestamp($cancelNoFeeDate) : null,
        );
    }
}
