<?php

namespace Module\Booking\Moderation\Domain\Booking\Service\StatusRules;

use Sdk\Shared\Enum\Booking\BookingStatusEnum;

final class OtherServiceTransitions extends AbstractTransitions implements StatusTransitionsInterface
{
    protected function configure(): void
    {
        $this->addTransition(BookingStatusEnum::CREATED, BookingStatusEnum::CANCELLED);
        $this->addTransition(BookingStatusEnum::CREATED, BookingStatusEnum::PROCESSING);
        $this->addTransition(BookingStatusEnum::PROCESSING, BookingStatusEnum::CONFIRMED);
        $this->addTransition(BookingStatusEnum::CONFIRMED, BookingStatusEnum::PROCESSING);
        $this->addTransition(BookingStatusEnum::CONFIRMED, BookingStatusEnum::CANCELLED);
    }
}
