<?php

declare(strict_types=1);

namespace Module\Client\Domain\Repository;

use Module\Client\Domain\Entity\Client;

interface ClientRepositoryInterface
{
    public function get(int $id): ?Client;
}
