<?php

declare(strict_types=1);

namespace Module\Client\Shared\Domain\Repository;

use Module\Client\Shared\Domain\ValueObject\ClientId;

interface ClientRequisitesRepositoryInterface
{
    public function getPhone(ClientId $clientId): ?string;

    public function getAddress(ClientId $clientId): ?string;

    public function getEmail(ClientId $clientId): ?string;
}
