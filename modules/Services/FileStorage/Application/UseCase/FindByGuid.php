<?php

namespace Module\Services\FileStorage\Application\UseCase;

use Module\Services\FileStorage\Application\Dto\FileDto;
use Sdk\Module\Contracts\Bus\QueryBusInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class FindByGuid implements UseCaseInterface
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
    ) {
    }

    public function execute(string $guid): ?FileDto
    {
        return $this->queryBus->execute(new \Module\Services\FileStorage\Application\Query\FindByGuid($guid));
    }
}