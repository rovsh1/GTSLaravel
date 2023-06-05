<?php

declare(strict_types=1);

namespace Module\Booking\Common\Application\Support\UseCase\Request;

use Module\Booking\Common\Application\Dto\RequestDto;
use Module\Booking\Common\Domain\Repository\RequestRepositoryInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetBookingRequests implements UseCaseInterface
{
    public function __construct(
        private readonly RequestRepositoryInterface $repository
    ) {}

    /**
     * @param int $bookingId
     * @return RequestDto[]
     */
    public function execute(int $bookingId): array
    {
        $requests = $this->repository->findByBookingId($bookingId);
        return RequestDto::collectionFromDomain($requests);
    }
}
