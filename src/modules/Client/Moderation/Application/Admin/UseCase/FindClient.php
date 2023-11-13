<?php

declare(strict_types=1);

namespace Module\Client\Moderation\Application\Admin\UseCase;

use Module\Client\Moderation\Application\Admin\Dto\ClientDto;
use Module\Client\Moderation\Domain\Repository\ClientRepositoryInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class FindClient implements UseCaseInterface
{
    public function __construct(
        private readonly ClientRepositoryInterface $repository
    ) {}

    public function execute(int $id): ?ClientDto
    {
        $client = $this->repository->get($id);
        if ($client === null) {
            return null;
        }

        return ClientDto::fromDomain($client);
    }
}