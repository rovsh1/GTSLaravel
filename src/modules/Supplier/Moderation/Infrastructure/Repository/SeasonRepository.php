<?php

declare(strict_types=1);

namespace Module\Supplier\Moderation\Infrastructure\Repository;

use Module\Supplier\Moderation\Domain\Supplier\Repository\SeasonRepositoryInterface;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\SeasonId;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\ServiceId;
use Module\Supplier\Moderation\Infrastructure\Models\Season;

class SeasonRepository implements SeasonRepositoryInterface
{
    public function findActiveSeasonId(ServiceId $serviceId, \DateTimeInterface $date): ?SeasonId
    {
        $season = Season::whereServiceId($serviceId->value())->whereIncludeDate($date)->first();
        if ($season === null) {
            return null;
        }

        return new SeasonId($season->id);
    }
}
