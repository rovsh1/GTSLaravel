<?php

declare(strict_types=1);

namespace Module\Booking\Requesting\Application\UseCase;

use Module\Booking\Moderation\Domain\Booking\Exception\OrderModeratingNotAllowed;
use Module\Booking\Moderation\Domain\Booking\Service\CheckOrderInModeratingMiddleware;
use Module\Booking\Requesting\Application\Dto\AvailableActionsDto;
use Module\Booking\Requesting\Domain\Service\RequestingRules;
use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetAvailableActions implements UseCaseInterface
{
    public function __construct(
        private readonly RequestingRules $requestRules,
        private readonly BookingRepositoryInterface $repository,
        private readonly CheckOrderInModeratingMiddleware $orderModeratingMiddleware,
    ) {}

    public function execute(int $bookingId): AvailableActionsDto
    {
        $booking = $this->repository->findOrFail(new BookingId($bookingId));

        try {
            $response = $this->orderModeratingMiddleware->handle($booking, function (Booking $booking) {
                $this->requestRules->booking($booking);

                return new AvailableActionsDto(
                    isRequestable: $this->requestRules->isBookingRequestable(),
                    canSendBookingRequest: $this->requestRules->canSendBookingRequest(),
                    canSendCancellationRequest: $this->requestRules->canSendCancellationRequest(),
                    canSendChangeRequest: $this->requestRules->canSendChangeRequest(),
                );
            });
        } catch (OrderModeratingNotAllowed $e) {
            $response = AvailableActionsDto::notAllowed();
        }

        return $response;
    }
}
