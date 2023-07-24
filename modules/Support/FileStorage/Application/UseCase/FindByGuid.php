<?php

namespace Module\Support\FileStorage\Application\UseCase;

use Module\Support\FileStorage\Application\Dto\FileDto;
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
        return $this->queryBus->execute(new \Module\Support\FileStorage\Application\Query\FindByGuid($guid));
    }
}