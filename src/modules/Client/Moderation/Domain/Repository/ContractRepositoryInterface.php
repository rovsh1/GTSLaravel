<?php

declare(strict_types=1);

namespace Module\Client\Moderation\Domain\Repository;

use Module\Client\Moderation\Domain\Entity\Contract;
use Module\Client\Shared\Domain\ValueObject\ClientId;

interface ContractRepositoryInterface
{
    public function findActive(ClientId $clientId): ?Contract;
}
