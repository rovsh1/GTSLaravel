<?php

declare(strict_types=1);

namespace Module\Booking\Requesting\Application\UseCase;

use Module\Booking\Requesting\Application\Dto\AvailableActionsDto;
use Module\Booking\Requesting\Domain\Booking\Service\RequestingRules;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetAvailableActions implements UseCaseInterface
{
    public function __construct(
        private readonly RequestingRules $requestRules,
        private readonly BookingRepositoryInterface $repository,
    ) {
    }

    public function execute(int $bookingId): AvailableActionsDto
    {
        $booking = $this->repository->findOrFail(new BookingId($bookingId));

        $this->requestRules->booking($booking);

        return new AvailableActionsDto(
            isRequestable: $this->requestRules->isBookingRequestable(),
            canSendBookingRequest: $this->requestRules->canSendBookingRequest(),
            canSendCancellationRequest: $this->requestRules->canSendCancellationRequest(),
            canSendChangeRequest: $this->requestRules->canSendChangeRequest(),
        );
    }
}
