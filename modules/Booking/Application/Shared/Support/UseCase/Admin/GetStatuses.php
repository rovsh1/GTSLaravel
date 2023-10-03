<?php

declare(strict_types=1);

namespace Module\Booking\Application\Shared\Support\UseCase\Admin;

use Module\Booking\Application\Shared\Response\StatusDto;
use Module\Booking\Application\Shared\Service\StatusStorage;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetStatuses implements UseCaseInterface
{
    public function __construct(private readonly StatusStorage $statusStorage) {}

    /**
     * @return StatusDto[]
     */
    public function execute(): array
    {
        return $this->statusStorage->statuses();
    }
}
