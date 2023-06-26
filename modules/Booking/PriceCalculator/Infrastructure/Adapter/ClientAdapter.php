<?php

declare(strict_types=1);

namespace Module\Booking\PriceCalculator\Infrastructure\Adapter;

use Module\Booking\PriceCalculator\Domain\Adapter\ClientAdapterInterface;
use Module\Client\Application\Response\ClientDto;

class ClientAdapter implements ClientAdapterInterface
{
    public function find(int $id): ?ClientDto
    {
        // TODO: Implement find() method.
    }
}
