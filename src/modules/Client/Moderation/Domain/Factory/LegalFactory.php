<?php

declare(strict_types=1);

namespace Module\Client\Moderation\Domain\Factory;

use Module\Client\Moderation\Domain\Entity\Legal;
use Module\Client\Moderation\Domain\ValueObject\BankRequisites;
use Module\Client\Moderation\Domain\ValueObject\IndustryId;
use Module\Client\Moderation\Domain\ValueObject\LegalId;
use Sdk\Module\Foundation\Support\EntityFactory\AbstractEntityFactory;

class LegalFactory extends AbstractEntityFactory
{
    protected string $entity = Legal::class;

    protected function fromArray(array $data): mixed
    {
        $bankRequisites = $data['requisites'] ?? null;
        if ($bankRequisites !== null) {
            $bankRequisites = BankRequisites::deserialize(json_decode($bankRequisites, true));
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
