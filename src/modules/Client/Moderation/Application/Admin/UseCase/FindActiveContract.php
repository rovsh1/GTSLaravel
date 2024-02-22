<?php

declare(strict_types=1);

namespace Module\Client\Moderation\Application\Admin\UseCase;

use Module\Client\Moderation\Application\Admin\Dto\ContractDto;
use Module\Client\Moderation\Domain\Repository\ContractRepositoryInterface;
use Module\Client\Shared\Domain\ValueObject\ClientId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class FindActiveContract implements UseCaseInterface
{
    public function __construct(
        private readonly ContractRepositoryInterface $repository
    ) {}

    public function execute(int $clientId): ?ContractDto
    {
        $client = $this->repository->findActive(new ClientId($clientId));
        if ($client === null) {
            return null;
        }

        return ContractDto::fromDomain($client);
    }
}
