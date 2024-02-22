<?php

namespace Module\Booking\Moderation\Domain\Booking\Service\StatusRules;

use Sdk\Booking\Enum\StatusEnum;

abstract class AbstractTransitions
{
    /**
     * @var array<int, StatusEnum[]> $transitions
     */
    protected array $transitions = [];

    public function __construct()
    {
        $this->configure();
    }

    abstract protected function configure(): void;

    protected function addTransition(StatusEnum $fromStatus, StatusEnum $toStatus): void
    {
        if (!isset($this->transitions[$fromStatus->value])) {
            $this->transitions[$fromStatus->value] = [];
        }

        $this->transitions[$fromStatus->value][] = $toStatus;
    }

//    /**
//     * @return BookingStatusEnum[]
//     */
//    public static function getCompletedStatuses(): array
//    {
//        return [
//            BookingStatusEnum::CONFIRMED,
//            BookingStatusEnum::CANCELLED_FEE,
//            BookingStatusEnum::CANCELLED_NO_FEE
//        ];
//    }

    public function canTransit(StatusEnum $fromStatus, StatusEnum $toStatus): bool
    {
        if (!isset($this->transitions[$fromStatus->value])) {
            return false;
        }

        return in_array($toStatus, $this->transitions[$fromStatus->value]);
    }

    /**
     * @param StatusEnum $statusEnum
     * @return StatusEnum[]
     */
    public function getAvailableTransitions(StatusEnum $statusEnum): array
    {
        return $this->transitions[$statusEnum->value] ?? [];
    }
}
