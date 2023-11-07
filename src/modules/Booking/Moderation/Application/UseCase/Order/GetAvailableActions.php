<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\Order;

use Module\Booking\Moderation\Application\Dto\OrderAvailableActionsDto;
use Module\Booking\Moderation\Application\Dto\StatusDto;
use Module\Booking\Moderation\Application\Factory\OrderStatusDtoFactory;
use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Order\Repository\OrderRepositoryInterface;
use Module\Booking\Shared\Domain\Order\ValueObject\OrderId;
use Module\Booking\Shared\Domain\Shared\Service\StatusRules\AdministratorRules;
use Module\Shared\Enum\Booking\BookingStatusEnum;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetAvailableActions implements UseCaseInterface
{
    public function __construct(
        private readonly AdministratorRules $statusRules,
        private OrderRepositoryInterface $repository,
        private readonly OrderStatusDtoFactory $statusDtoFactory,
    ) {}

    public function execute(int $orderId): OrderAvailableActionsDto
    {
        $order = $this->repository->findOrFail(new OrderId($orderId));
//        $statuses = $this->getAvailableStatuses($booking);
        $statuses = [];

        return new OrderAvailableActionsDto(
            $statuses,
//            $this->statusRules->isEditableStatus($booking->status()),
            true,
            false,
            false,
        );
    }

    /**
     * @param Booking $booking
     * @return StatusDto[]
     */
    private function getAvailableStatuses(Booking $booking): array
    {
        $statuses = $this->statusRules->getStatusTransitions($booking->status());
        $statusIds = array_flip(array_map(fn(BookingStatusEnum $status) => $status->value, $statuses));
        $statusSettings = $this->statusDtoFactory->statuses();

        return array_values(
            array_filter($statusSettings, fn(StatusDto $statusDto) => array_key_exists($statusDto->id, $statusIds))
        );
    }
}
