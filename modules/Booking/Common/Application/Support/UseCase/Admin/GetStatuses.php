<?php

declare(strict_types=1);

namespace Module\Booking\Common\Application\Support\UseCase\Admin;

use Module\Booking\Common\Application\Query\Admin\GetStatusSettings;
use Module\Booking\Common\Application\Response\StatusDto;
use Sdk\Module\Contracts\Bus\QueryBusInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetStatuses implements UseCaseInterface
{
    public function __construct(private readonly QueryBusInterface $queryBus) {}

    /**
     * @return StatusDto[]
     */
    public function execute(): array
    {
        return $this->queryBus->execute(new GetStatusSettings());
    }
}
