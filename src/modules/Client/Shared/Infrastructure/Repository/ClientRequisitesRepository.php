<?php

declare(strict_types=1);

namespace Module\Client\Shared\Infrastructure\Repository;

use Module\Client\Shared\Domain\Repository\ClientRequisitesRepositoryInterface;
use Module\Client\Shared\Domain\ValueObject\ClientId;
use Module\Client\Shared\Infrastructure\Models\Contact;

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
