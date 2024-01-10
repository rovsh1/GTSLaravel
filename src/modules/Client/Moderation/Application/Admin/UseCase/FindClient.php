<?php

declare(strict_types=1);

namespace Module\Client\Moderation\Application\Admin\UseCase;

use Module\Client\Moderation\Application\Admin\Dto\ClientDto;
use Module\Client\Shared\Domain\Repository\ClientRepositoryInterface;
use Module\Client\Shared\Domain\Repository\ClientRequisitesRepositoryInterface;
use Module\Client\Shared\Domain\ValueObject\ClientId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class FindClient implements UseCaseInterface
{
    public function __construct(
        private readonly ClientRepositoryInterface $repository,
        private readonly ClientRequisitesRepositoryInterface $clientRequisitesRepository,
    ) {}

    public function execute(int $id): ?ClientDto
    {
        $client = $this->repository->find(new ClientId($id));
        if ($client === null) {
            return null;
        }

        return new ClientDto(
            $client->id()->value(),
            $client->name(),
            $client->type()->value,
            $client->residency(),
            $client->language(),
            $this->clientRequisitesRepository->getAddress($client->id()),
            $this->clientRequisitesRepository->getPhone($client->id()),
            $this->clientRequisitesRepository->getEmail($client->id()),
        );
    }
}
