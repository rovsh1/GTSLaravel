<?php

declare(strict_types=1);

namespace Module\Pricing\Domain\Markup\Repository;

use Module\Client\Domain\ValueObject\ClientId;
use Module\Pricing\Domain\Markup\Entity\ClientMarkup;

interface ClientMarkupRepositoryInterface
{
    public function find(ClientId $id): ?ClientMarkup;
}
