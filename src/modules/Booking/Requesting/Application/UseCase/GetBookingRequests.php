<?php

declare(strict_types=1);

namespace Module\Booking\Requesting\Application\UseCase;

use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Requesting\Application\Dto\RequestDto;
use Module\Booking\Requesting\Domain\BookingRequest\Repository\RequestRepositoryInterface;
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
