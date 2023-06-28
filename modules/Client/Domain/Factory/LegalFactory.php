<?php

declare(strict_types=1);

namespace Module\Client\Domain\Factory;

use Module\Client\Domain\Entity\Legal;
use Module\Client\Domain\ValueObject\BankRequisites;
use Module\Shared\Domain\Service\SerializerInterface;
use Module\Shared\Domain\ValueObject\Id;
use Module\Shared\Enum\Client\LegalTypeEnum;
use Sdk\Module\Foundation\Support\EntityFactory\AbstractEntityFactory;

class LegalFactory extends AbstractEntityFactory
{
    protected string $entity = Legal::class;

    public function __construct(
        private readonly SerializerInterface $serializer
    ) {
        parent::__construct();
    }

    protected function fromArray(array $data): mixed
    {
        return new $this->entity(
            new Id($data['id']),
            $data['name'],
            LegalTypeEnum::from($data['type']),
            $this->serializer->deserialize(BankRequisites::class, $data['requisites'])
        );
    }
}
