<?php

declare(strict_types=1);

namespace Module\Supplier\Moderation\Infrastructure\Repository;

use Module\Shared\ValueObject\Percent;
use Module\Supplier\Moderation\Domain\Supplier\Repository\CancelConditionsRepositoryInterface;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\CancelConditions;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\CancelMarkupOption;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\DailyMarkupCollection;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\DailyMarkupOption;

class CancelConditionsRepository implements CancelConditionsRepositoryInterface
{
    public function get(): CancelConditions
    {
        return new CancelConditions(
            noCheckInMarkup: new CancelMarkupOption(
                percent: new Percent(100)
            ),
            dailyMarkups: new DailyMarkupCollection(
                [
                    new DailyMarkupOption(
                        new Percent(50),
                        2
                    )
                ]
            )
        );
    }

    public function store(CancelConditions $cancelConditions): bool
    {
        return false;
    }
}
