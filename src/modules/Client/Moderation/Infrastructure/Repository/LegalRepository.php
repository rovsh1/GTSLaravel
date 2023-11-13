<?php

declare(strict_types=1);

namespace Module\Client\Moderation\Infrastructure\Repository;

use Module\Client\Moderation\Domain\Entity\Legal;
use Module\Client\Moderation\Domain\Factory\LegalFactory;
use Module\Client\Moderation\Domain\Repository\LegalRepositoryInterface;
use Module\Client\Moderation\Infrastructure\Models\Legal as Model;
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