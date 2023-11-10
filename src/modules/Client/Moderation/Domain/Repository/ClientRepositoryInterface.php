<?php

declare(strict_types=1);

namespace Module\Client\Moderation\Domain\Repository;

use Module\Client\Moderation\Domain\Entity\Client;

interface ClientRepositoryInterface
{
    public function get(int $id): ?Client;
}
