<?php

declare(strict_types=1);

namespace Module\Supplier\Moderation\Domain\Supplier\ValueObject;

use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Sdk\Shared\Contracts\Support\SerializableInterface;

class CancelConditions implements ValueObjectInterface, SerializableInterface
{
    public function __construct(
        private readonly CancelMarkupOption $noCheckInMarkup,
        private readonly DailyMarkupCollection $dailyMarkups
    ) {}

    public function noCheckInMarkup(): CancelMarkupOption
    {
        return $this->noCheckInMarkup;
    }

    public function dailyMarkups(): DailyMarkupCollection
    {
        return $this->dailyMarkups;
    }

    public function serialize(): array
    {
        return [
            'noCheckInMarkup' => $this->noCheckInMarkup->serialize(),
            'dailyMarkups' => $this->dailyMarkups->serialize(),
        ];
    }

    public static function deserialize(array $payload): static
    {
        return new static(
            CancelMarkupOption::deserialize($payload['noCheckInMarkup']),
            DailyMarkupCollection::deserialize($payload['dailyMarkups']),
        );
    }
}
