<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\Booking\UseCase\Request;

use Module\Booking\Application\Admin\Shared\Response\RequestDto;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\BookingRequest\Repository\RequestRepositoryInterface;
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
