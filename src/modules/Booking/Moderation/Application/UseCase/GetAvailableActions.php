<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase;

use Module\Booking\Moderation\Application\Dto\AvailableActionsDto;
use Module\Booking\Moderation\Application\Service\EditRules;
use Module\Booking\Moderation\Domain\Booking\Exception\OrderModeratingNotAllowed;
use Module\Booking\Moderation\Domain\Booking\Service\CheckOrderInModeratingMiddleware;
use Module\Booking\Moderation\Domain\Booking\Service\StatusRules\StatusTransitionsFactory;
use Module\Booking\Shared\Application\Factory\BookingStatusDtoFactory;
use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Sdk\Booking\Dto\StatusDto;
use Sdk\Booking\Enum\StatusEnum;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetAvailableActions implements UseCaseInterface
{
    public function __construct(
        private readonly StatusTransitionsFactory $statusTransitionsFactory,
        private readonly EditRules $editRules,
        private readonly BookingRepositoryInterface $repository,
        private readonly BookingStatusDtoFactory $statusDtoFactory,
        private readonly CheckOrderInModeratingMiddleware $orderModeratingMiddleware,
    ) {}

    public function execute(int $bookingId): AvailableActionsDto
    {
        $booking = $this->repository->findOrFail(new BookingId($bookingId));

        try {
            $response = $this->orderModeratingMiddleware->handle($booking, function (Booking $booking) {
                $this->editRules->booking($booking);

                return new AvailableActionsDto(
                    statuses: $this->buildAvailableStatuses($booking),
                    isEditable: $this->editRules->isEditable(),
                    canEditExternalNumber: $this->editRules->canEditExternalNumber(),
                    //@todo прописать логику для этого флага (у отеля и админки она разная)
                    canChangeRoomPrice: $this->editRules->canChangeRoomPrice(),
                    canCopy: $this->editRules->canCopy(),
                );
            });
        } catch (OrderModeratingNotAllowed $e) {
            $response = AvailableActionsDto::notAllowed();
        }

        return $response;
    }

    /**
     * @param Booking $booking
     * @return StatusDto[]
     */
    private function buildAvailableStatuses(Booking $booking): array
    {
        $statusTransitions = $this->statusTransitionsFactory->build($booking->serviceType());

        return array_map(
            fn(StatusEnum $s) => $this->statusDtoFactory->get($s),
            $statusTransitions->getAvailableTransitions($booking->status())
        );
    }
}
