<?php

declare(strict_types=1);

namespace Module\Client\Moderation\Application\Admin\UseCase;

use Module\Client\Moderation\Application\Admin\Dto\LegalDto;
use Module\Client\Moderation\Domain\Repository\LegalRepositoryInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class FindLegal implements UseCaseInterface
{
    public function __construct(
        private readonly LegalRepositoryInterface $repository
    ) {}

    public function execute(int $id): ?LegalDto
    {
        $client = $this->repository->get($id);
        if ($client === null) {
            return null;
        }

        return LegalDto::fromDomain($client);
    }
}
