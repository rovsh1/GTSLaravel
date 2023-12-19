<?php

declare(strict_types=1);

namespace Module\Client\Moderation\Infrastructure\Repository;

use Module\Client\Moderation\Domain\Repository\ClientRequisitesRepositoryInterface;
use Module\Client\Moderation\Infrastructure\Models\Contact;
use Module\Client\Shared\Domain\ValueObject\ClientId;

class ClientRequisitesRepository implements ClientRequisitesRepositoryInterface
{
    public function getPhone(ClientId $clientId): ?string
    {
        $model = Contact::whereClientId($clientId->value())->whereIsPhone()->whereMain()->first();

        return $model?->value;
    }

    public function getAddress(ClientId $clientId): ?string
    {
        $model = Contact::whereClientId($clientId->value())->whereIsAddress()->whereMain()->first();

        return $model?->value;
    }

    public function getEmail(ClientId $clientId): ?string
    {
        $model = Contact::whereClientId($clientId->value())->whereIsEmail()->whereMain()->first();

        return $model?->value;
    }
}
