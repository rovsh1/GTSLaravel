<?php

declare(strict_types=1);

namespace Module\Client\Domain\Factory;

use Module\Client\Domain\Entity\Legal;
use Module\Client\Domain\ValueObject\BankRequisites;
use Module\Client\Domain\ValueObject\IndustryId;
use Module\Client\Domain\ValueObject\LegalId;
use Module\Shared\Domain\Service\SerializerInterface;
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
        $bankRequisites = $data['requisites'] ?? null;
        if($bankRequisites !== null) {
            $bankRequisites = $this->serializer->deserialize(BankRequisites::class, $bankRequisites);
        }
        return new $this->entity(
            new LegalId($data['id']),
            $data['name'],
            new IndustryId($data['industry_id']),
            $data['address'],
            LegalTypeEnum::from($data['type']),
            $bankRequisites
        );
    }
}
