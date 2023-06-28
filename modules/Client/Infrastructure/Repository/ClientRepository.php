<?php

declare(strict_types=1);

namespace Module\Client\Infrastructure\Repository;

use Module\Client\Domain\Entity\Client;
use Module\Client\Domain\Factory\ClientFactory;
use Module\Client\Domain\Repository\ClientRepositoryInterface;
use Module\Client\Infrastructure\Models\Client as Model;

class ClientRepository implements ClientRepositoryInterface
{
    public function __construct(
        private readonly ClientFactory $factory
    ) {}

    public function get(int $id): ?Client
    {
        $model = Model::find($id);
        if ($model === null) {
            return null;
        }

        return $this->factory->createFrom($model);
    }
}
