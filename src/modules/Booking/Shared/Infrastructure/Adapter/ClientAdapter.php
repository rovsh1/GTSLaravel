<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Adapter;

use Module\Booking\Shared\Domain\Shared\Adapter\ClientAdapterInterface;
use Module\Client\Moderation\Application\Admin\Dto\ClientDto;
use Module\Client\Moderation\Application\Admin\Dto\ContractDto;
use Module\Client\Moderation\Application\Admin\Dto\LegalDto;
use Module\Client\Moderation\Application\Admin\UseCase\FindActiveContract;
use Module\Client\Moderation\Application\Admin\UseCase\FindClient;
use Module\Client\Moderation\Application\Admin\UseCase\FindLegal;

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

    public function findContract(int $clientId): ?ContractDto
    {
        return app(FindActiveContract::class)->execute($clientId);
    }
}
