<?php

declare(strict_types=1);

namespace Module\Booking\Common\Application\Support\UseCase\Admin;

use Module\Booking\Common\Domain\Repository\BookingChangesLogRepositoryInterface;
use Module\Booking\Hotel\Application\Dto\StatusEventDto;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetStatusHistory implements UseCaseInterface
{
    public function __construct(
        private readonly BookingChangesLogRepositoryInterface $changesLogRepository
    ) {}

    public function execute(int $id): array
    {
        $statusEvents = $this->changesLogRepository->getStatusHistory($id);

        return StatusEventDto::collectionFromDomain($statusEvents);
    }
}
