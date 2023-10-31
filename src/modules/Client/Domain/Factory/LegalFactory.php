<?php

declare(strict_types=1);

namespace Module\Client\Domain\Factory;

use Module\Client\Domain\Entity\Legal;
use Module\Client\Domain\ValueObject\BankRequisites;
use Module\Client\Domain\ValueObject\IndustryId;
use Module\Client\Domain\ValueObject\LegalId;
use Module\Shared\Contracts\Service\SerializerInterface;
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
        if ($bankRequisites !== null) {
            $bankRequisites = $this->serializer->deserialize(BankRequisites::class, $bankRequisites);
        }
        $industryId = $data['industry_id'] ?? null;

        return new $this->entity(
            new LegalId($data['id']),
            $data['name'],
            $industryId !== null ? new IndustryId($industryId) : null,
            $data['address'],
            $bankRequisites
        );
    }
}
