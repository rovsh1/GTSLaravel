<?php

namespace Module\Booking\Moderation\Domain\Booking\Service\StatusRules;

use Sdk\Shared\Enum\Booking\BookingStatusEnum;

abstract class AbstractTransitions
{
    /**
     * @var array<int, BookingStatusEnum[]> $transitions
     */
    protected array $transitions = [];

    public function __construct()
    {
        $this->configure();
    }

    abstract protected function configure(): void;

    protected function addTransition(BookingStatusEnum $fromStatus, BookingStatusEnum $toStatus): void
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

    public function canTransit(BookingStatusEnum $fromStatus, BookingStatusEnum $toStatus): bool
    {
        if (!isset($this->transitions[$fromStatus->value])) {
            return false;
        }

        return in_array($toStatus, $this->transitions[$fromStatus->value]);
    }

    /**
     * @param BookingStatusEnum $statusEnum
     * @return BookingStatusEnum[]
     */
    public function getAvailableTransitions(BookingStatusEnum $statusEnum): array
    {
        return $this->transitions[$statusEnum->value] ?? [];
    }
}
