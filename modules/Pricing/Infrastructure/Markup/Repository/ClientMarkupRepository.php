<?php

declare(strict_types=1);

namespace Module\Pricing\Infrastructure\Markup\Repository;

use Module\Client\Domain\ValueObject\ClientId;
use Module\Pricing\Domain\Markup\Entity\ClientMarkup;
use Module\Pricing\Domain\Markup\Repository\ClientMarkupRepositoryInterface;

class ClientMarkupRepository implements ClientMarkupRepositoryInterface
{
    public function find(ClientId $id): ?ClientMarkup
    {
        // TODO: Implement find() method.
    }
}
