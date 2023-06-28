<?php

declare(strict_types=1);

namespace Module\Client\Domain\Repository;

use Module\Client\Domain\Entity\Legal;

interface LegalRepositoryInterface
{
    public function get(int $id): ?Legal;
}
