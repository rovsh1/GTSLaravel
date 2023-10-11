<?php

declare(strict_types=1);

namespace Module\Client\Infrastructure\Repository;

use Module\Client\Domain\Entity\Legal;
use Module\Client\Domain\Factory\LegalFactory;
use Module\Client\Domain\Repository\LegalRepositoryInterface;
use Module\Client\Infrastructure\Models\Legal as Model;
use Module\Shared\Contracts\Service\SerializerInterface;

class LegalRepository implements LegalRepositoryInterface
{
    public function __construct(
        private readonly LegalFactory $factory,
        private readonly SerializerInterface $serializer
    ) {}

    public function get(int $id): ?Legal
    {
        $model = Model::find($id);
        if ($model === null) {
            return null;
        }

        return $this->factory->createFrom($model);
    }

    public function store(Legal $legal): bool
    {
        return (bool)Model::whereId($legal->id()->value())->update([
            'name' => $legal->name(),
            'requisites' => $legal->requisites() !== null
                ? $this->serializer->serialize($legal->requisites())
                : null,
        ]);
    }
}
