<?php

declare(strict_types=1);

namespace Module\Supplier\Moderation\Domain\Supplier\Repository;

use Module\Supplier\Moderation\Domain\Supplier\Entity\Service;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\ServiceId;

interface ServiceRepositoryInterface
{
    public function find(ServiceId $id): ?Service;
}
