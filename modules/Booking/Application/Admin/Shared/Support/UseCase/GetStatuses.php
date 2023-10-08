<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\Shared\Support\UseCase;

use Module\Booking\Application\Admin\Shared\Response\StatusDto;
use Module\Booking\Application\Admin\Shared\Service\StatusStorage;
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
