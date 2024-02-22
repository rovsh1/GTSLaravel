<?php

declare(strict_types=1);

namespace Module\Supplier\Moderation\Domain\Supplier\Repository;

use Module\Supplier\Moderation\Domain\Supplier\ValueObject\SeasonId;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\ServiceId;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\SupplierId;

interface SeasonRepositoryInterface
{
    public function findActiveSeasonId(ServiceId $serviceId, \DateTimeInterface $date): ?SeasonId;
}
