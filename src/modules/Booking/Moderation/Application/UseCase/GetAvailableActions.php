<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase;

use Module\Booking\Moderation\Application\Dto\AvailableActionsDto;
use Module\Booking\Moderation\Application\Factory\StatusSettingsDtoFactory;
use Module\Booking\Moderation\Application\Service\EditRules;
use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\DetailsRepositoryInterface;
use Sdk\Booking\Dto\StatusDto;
use Sdk\Booking\Enum\StatusEnum;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Shared\Enum\ServiceTypeEnum;
use Shared\Contracts\Adapter\TravelineAdapterInterface;

class GetAvailableActions implements UseCaseInterface
{
    public function __construct(
        private readonly EditRules $editRules,
        private readonly BookingRepositoryInterface $repository,
        private readonly StatusSettingsDtoFactory $statusSettingsDtoFactory,
        private readonly DetailsRepositoryInterface $detailsRepository,
        private readonly TravelineAdapterInterface $travelineAdapter,
    ) {}

    public function execute(int $bookingId): AvailableActionsDto
    {
        $booking = $this->repository->findOrFail(new BookingId($bookingId));

        $this->editRules->booking($booking);

        $isTravelineIntegrationEnabled = false;
        if ($booking->serviceType() === ServiceTypeEnum::HOTEL_BOOKING) {
            $details = $this->detailsRepository->findOrFail($booking->id());
            $hotelId = $details->hotelInfo()->id();
            $isTravelineIntegrationEnabled = $this->travelineAdapter->isHotelIntegrationEnabled($hotelId);
        }

        //@todo refactor - ограничивать доменную логику в зависимости от available actions
        return new AvailableActionsDto(
            statuses: $this->buildAvailableStatuses(),
            isEditable: $this->editRules->isEditable(),
            canEditExternalNumber: $this->editRules->canEditExternalNumber(),
            canChangeRoomPrice: !$isTravelineIntegrationEnabled && $this->editRules->canChangeRoomPrice(),
            canCopy: $this->editRules->canCopy(),
            canEditFinancialCost: $this->editRules->canEditFinancialCost(),
        );
    }

    /**
     * @param Booking $booking
     * @return StatusDto[]
     */
    private function buildAvailableStatuses(): array
    {
        return array_map(
            fn(StatusEnum $s) => $this->statusSettingsDtoFactory->get($s),
            $this->editRules->getAvailableStatusTransitions()
        );
    }
}
