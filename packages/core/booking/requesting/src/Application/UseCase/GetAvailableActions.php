<?php

declare(strict_types=1);

namespace Pkg\Booking\Requesting\Application\UseCase;

use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\DetailsRepositoryInterface;
use Pkg\Booking\Requesting\Application\Dto\AvailableActionsDto;
use Pkg\Booking\Requesting\Domain\Service\RequestingRules;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Shared\Enum\ServiceTypeEnum;
use Shared\Contracts\Adapter\TravelineAdapterInterface;

class GetAvailableActions implements UseCaseInterface
{
    public function __construct(
        private readonly RequestingRules $requestRules,
        private readonly BookingRepositoryInterface $repository,
        private readonly DetailsRepositoryInterface $detailsRepository,
        private readonly TravelineAdapterInterface $travelineAdapter,
    ) {}

    public function execute(int $bookingId): AvailableActionsDto
    {
        $booking = $this->repository->findOrFail(new BookingId($bookingId));

        $this->requestRules->booking($booking);

        $isTravelineIntegrationEnabled = false;
        if ($booking->serviceType() === ServiceTypeEnum::HOTEL_BOOKING) {
            $details = $this->detailsRepository->findOrFail($booking->id());
            $hotelId = $details->hotelInfo()->id();
            $isTravelineIntegrationEnabled = $this->travelineAdapter->isHotelIntegrationEnabled($hotelId);
        }

        //@todo refactor - ограничивать доменную логику в зависимости от available actions
        return new AvailableActionsDto(
            isRequestable: !$isTravelineIntegrationEnabled && $this->requestRules->isBookingRequestable(),
            canSendBookingRequest: !$isTravelineIntegrationEnabled && $this->requestRules->canSendBookingRequest(),
            canSendCancellationRequest: !$isTravelineIntegrationEnabled && $this->requestRules->canSendCancellationRequest(),
            canSendChangeRequest: !$isTravelineIntegrationEnabled && $this->requestRules->canSendChangeRequest(),
        );
    }
}
