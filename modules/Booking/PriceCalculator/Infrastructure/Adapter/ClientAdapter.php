<?php

declare(strict_types=1);

namespace Module\Booking\PriceCalculator\Infrastructure\Adapter;

use Module\Booking\PriceCalculator\Domain\Adapter\ClientAdapterInterface;
use Module\Client\Application\Dto\ClientDto;
use Module\Client\Application\Dto\LegalDto;
use Module\Client\Application\UseCase\FindClient;
use Module\Client\Application\UseCase\FindLegal;

class ClientAdapter implements ClientAdapterInterface
{
    public function find(int $id): ?ClientDto
    {
        return app(FindClient::class)->execute($id);
    }

    public function findLegal(int $id): ?LegalDto
    {
        return app(FindLegal::class)->execute($id);
    }
}
