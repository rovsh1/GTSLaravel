<?php

declare(strict_types=1);

namespace Module\Client\Shared\Domain\Repository;

use Module\Client\Shared\Domain\Entity\Client;
use Module\Client\Shared\Domain\ValueObject\ClientId;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

interface ClientRepositoryInterface
{
    public function find(ClientId $id): ?Client;

    /**
     * @param ClientId $id
     * @return Client
     * @throws EntityNotFoundException
     */
    public function findOrFail(ClientId $id): Client;
}
