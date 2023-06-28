<?php

declare(strict_types=1);

namespace Module\Client\Infrastructure\Repository;

use Module\Client\Domain\Entity\Legal;
use Module\Client\Domain\Factory\LegalFactory;
use Module\Client\Domain\Repository\LegalRepositoryInterface;
use Module\Client\Infrastructure\Models\Legal as Model;

class LegalRepository implements LegalRepositoryInterface
{
    public function __construct(
        private readonly LegalFactory $factory
    ) {}

    public function get(int $id): ?Legal
    {
        $model = Model::find($id);
        if ($model === null) {
            return null;
        }

        return $this->factory->createFrom($model);
    }
}
