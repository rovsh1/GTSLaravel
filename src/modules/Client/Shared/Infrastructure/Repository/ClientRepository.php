<?php

declare(strict_types=1);

namespace Module\Client\Shared\Infrastructure\Repository;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Module\Client\Shared\Domain\Entity\Client;
use Module\Client\Shared\Domain\Factory\ClientFactory;
use Module\Client\Shared\Domain\Repository\ClientRepositoryInterface;
use Module\Client\Shared\Domain\ValueObject\ClientId;
use Module\Client\Shared\Infrastructure\Models\Client as Model;

class ClientRepository implements ClientRepositoryInterface
{
    public function __construct(
        private readonly ClientFactory $factory
    ) {}

    public function find(ClientId $id): ?Client
    {
        $model = Model::find($id);
        if ($model === null) {
            return null;
        }

        return $this->factory->createFrom($model);
    }

    public function findOrFail(ClientId $id): Client
    {
        return $this->find($id) ?? throw new ModelNotFoundException();
    }
}
