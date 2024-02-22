<?php

declare(strict_types=1);

namespace Pkg\Booking\Requesting\Application\UseCase;

use Pkg\Booking\Requesting\Application\Dto\RequestDto;
use Pkg\Booking\Requesting\Domain\Repository\RequestRepositoryInterface;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetBookingRequests implements UseCaseInterface
{
    public function __construct(
        private readonly RequestRepositoryInterface $repository
    ) {
    }

    /**
     * @param int $bookingId
     * @return RequestDto[]
     */
    public function execute(int $bookingId): array
    {
        $requests = $this->repository->findByBookingId(new BookingId($bookingId));

        return array_map(fn($r) => new RequestDto(
            id: $r->id()->value(),
            type: $r->type()->value,
            dateCreate: $r->dateCreate()
        ), $requests);
    }
}
