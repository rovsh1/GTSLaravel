<?php

declare(strict_types=1);

namespace Module\Client\Moderation\Infrastructure\Repository;

use Module\Client\Moderation\Domain\Entity\Client;
use Module\Client\Moderation\Domain\Factory\ClientFactory;
use Module\Client\Moderation\Domain\Repository\ClientRepositoryInterface;
use Module\Client\Moderation\Infrastructure\Models\Client as Model;

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
