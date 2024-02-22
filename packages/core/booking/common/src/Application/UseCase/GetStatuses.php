<?php

declare(strict_types=1);

namespace Pkg\Booking\Common\Application\UseCase;

use Module\Booking\Moderation\Application\Factory\StatusSettingsDtoFactory;
use Sdk\Booking\Dto\StatusDto;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetStatuses implements UseCaseInterface
{
    public function __construct(private readonly StatusSettingsDtoFactory $statusSettingsDtoFactory) {}

    /**
     * @return StatusDto[]
     */
    public function execute(): array
    {
        return $this->statusSettingsDtoFactory->statuses();
    }
}
