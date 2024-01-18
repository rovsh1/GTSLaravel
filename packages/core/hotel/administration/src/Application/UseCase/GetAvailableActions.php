<?php

declare(strict_types=1);


use Module\Booking\Moderation\Application\Dto\AvailableActionsDto;
use Module\Booking\Moderation\Application\Factory\StatusSettingsDtoFactory;
use Module\Booking\Moderation\Application\Service\EditRules;
use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Sdk\Booking\Dto\StatusDto;
use Sdk\Booking\Enum\StatusEnum;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetAvailableActions implements UseCaseInterface
{
    public function __construct(
        private readonly EditRules $editRules,
        private readonly BookingRepositoryInterface $repository,
        private readonly StatusSettingsDtoFactory $statusSettingsDtoFactory,
    ) {}

    public function execute(int $bookingId): AvailableActionsDto
    {
        $booking = $this->repository->findOrFail(new BookingId($bookingId));

        $this->editRules->booking($booking);

        //@todo refactor - ограничивать доменную логику в зависимости от available actions

        return new AvailableActionsDto(
            statuses: $this->buildAvailableStatuses(),
            isEditable: $this->editRules->isEditable(),
            canEditExternalNumber: $this->editRules->canEditExternalNumber(),
            canChangeRoomPrice: $this->editRules->canChangeRoomPrice(),
            canCopy: $this->editRules->canCopy(),
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
