<?php

declare(strict_types=1);

namespace Module\Client\Moderation\Domain\Repository;

use Module\Client\Moderation\Domain\Entity\Legal;

interface LegalRepositoryInterface
{
    public function get(int $id): ?Legal;

    public function store(Legal $legal): bool;
}
