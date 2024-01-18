<?php

declare(strict_types=1);

namespace Pkg\Booking\Requesting\Application\UseCase;

use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Pkg\Booking\Requesting\Application\Dto\AvailableActionsDto;
use Pkg\Booking\Requesting\Domain\Service\RequestingRules;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetAvailableActions implements UseCaseInterface
{
    public function __construct(
        private readonly RequestingRules $requestRules,
        private readonly BookingRepositoryInterface $repository,
    ) {}

    public function execute(int $bookingId): AvailableActionsDto
    {
        $booking = $this->repository->findOrFail(new BookingId($bookingId));

        $this->requestRules->booking($booking);

        //@todo refactor - ограничивать доменную логику в зависимости от available actions
        return new AvailableActionsDto(
            isRequestable: $this->requestRules->isBookingRequestable(),
            canSendBookingRequest: $this->requestRules->canSendBookingRequest(),
            canSendCancellationRequest: $this->requestRules->canSendCancellationRequest(),
            canSendChangeRequest: $this->requestRules->canSendChangeRequest(),
        );
    }
}
