<?php

declare(strict_types=1);

namespace Module\Booking\Application\Shared\Support\UseCase\Admin\Request;

use Module\Booking\Application\Shared\Response\RequestDto;
use Module\Booking\Domain\Shared\Repository\RequestRepositoryInterface;
use Module\Booking\Domain\Shared\ValueObject\BookingId;
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
        $requests = $this->repository->findByBookingId(new BookingId($bookingId));
        return RequestDto::collectionFromDomain($requests);
    }
}
