<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase;

use Module\Booking\Moderation\Application\Dto\AvailableActionsDto;
use Module\Booking\Moderation\Application\Service\EditRules;
use Module\Booking\Moderation\Domain\Booking\Service\StatusRules\StatusTransitionsFactory;
use Module\Booking\Shared\Application\Factory\BookingStatusDtoFactory;
use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Sdk\Booking\Dto\StatusDto;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Shared\Enum\Booking\BookingStatusEnum;

class GetAvailableActions implements UseCaseInterface
{
    public function __construct(
        private readonly StatusTransitionsFactory $statusTransitionsFactory,
        private readonly EditRules $editRules,
        private readonly BookingRepositoryInterface $repository,
        private readonly BookingStatusDtoFactory $statusDtoFactory,
    ) {
    }

    public function execute(int $bookingId): AvailableActionsDto
    {
        $booking = $this->repository->findOrFail(new BookingId($bookingId));

        $this->editRules->booking($booking);

        return new AvailableActionsDto(
            statuses: $this->buildAvailableStatuses($booking),
            isEditable: $this->editRules->isEditable(),
            canEditExternalNumber: $this->editRules->canEditExternalNumber(),
            //@todo прописать логику для этого флага (у отеля и админки она разная)
            canChangeRoomPrice: $this->editRules->canChangeRoomPrice(),
            canCopy: $this->editRules->canCopy(),
        );
    }

    /**
     * @param Booking $booking
     * @return StatusDto[]
     */
    private function buildAvailableStatuses(Booking $booking): array
    {
        $statusTransitions = $this->statusTransitionsFactory->build($booking->serviceType());

        return array_map(
            fn(BookingStatusEnum $s) => $this->statusDtoFactory->get($s),
            $statusTransitions->getAvailableTransitions($booking->status())
        );
    }
}
