<?php

declare(strict_types=1);

namespace Module\Supplier\Domain\Supplier\ValueObject;

use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Contracts\Support\SerializableDataInterface;

class CancelConditions implements ValueObjectInterface, SerializableDataInterface
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

    public function toData(): array
    {
        return [
            'noCheckInMarkup' => $this->noCheckInMarkup->toData(),
            'dailyMarkups' => $this->dailyMarkups->toData(),
        ];
    }

    public static function fromData(array $data): static
    {
        return new static(
            CancelMarkupOption::fromData($data['noCheckInMarkup']),
            DailyMarkupCollection::fromData($data['dailyMarkups']),
        );
    }
}
