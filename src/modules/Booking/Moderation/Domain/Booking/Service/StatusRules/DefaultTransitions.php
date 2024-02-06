<?php

namespace Module\Booking\Moderation\Domain\Booking\Service\StatusRules;

use Sdk\Booking\Enum\StatusEnum;

final class DefaultTransitions extends AbstractTransitions implements StatusTransitionsInterface
{
    protected function configure(): void
    {
        $this->addTransition(StatusEnum::DRAFT, StatusEnum::CREATED);

        $this->addTransition(StatusEnum::CREATED, StatusEnum::PROCESSING);
        $this->addTransition(StatusEnum::CREATED, StatusEnum::CANCELLED);

        $this->addTransition(StatusEnum::PROCESSING, StatusEnum::CANCELLED);
        //$this->addTransition(BookingStatusEnum::PROCESSING, BookingStatusEnum::WAITING_CONFIRMATION);

        $this->addTransition(StatusEnum::WAITING_CONFIRMATION, StatusEnum::CONFIRMED);
        $this->addTransition(StatusEnum::WAITING_CONFIRMATION, StatusEnum::NOT_CONFIRMED);

        //$this->addTransition(BookingStatusEnum::NOT_CONFIRMED, BookingStatusEnum::WAITING_CONFIRMATION);
        $this->addTransition(StatusEnum::NOT_CONFIRMED, StatusEnum::CANCELLED);

        $this->addTransition(StatusEnum::CONFIRMED, StatusEnum::WAITING_PROCESSING);
//        $this->addTransition(BookingStatusEnum::CONFIRMED, BookingStatusEnum::WAITING_CANCELLATION);
        //$this->addTransition(BookingStatusEnum::CONFIRMED, BookingStatusEnum::CANCELLED_FEE);
        //$this->addTransition(BookingStatusEnum::CONFIRMED, BookingStatusEnum::CANCELLED_NO_FEE);

        //$this->addTransition(BookingStatusEnum::WAITING_PROCESSING, BookingStatusEnum::WAITING_CONFIRMATION);

        //$this->addTransition(BookingStatusEnum::INVOICED, BookingStatusEnum::WAITING_CANCELLATION);

//        $this->addTransition(StatusEnum::WAITING_CANCELLATION, StatusEnum::CANCELLED);
        $this->addTransition(StatusEnum::WAITING_CANCELLATION, StatusEnum::CANCELLED_FEE);
        $this->addTransition(StatusEnum::WAITING_CANCELLATION, StatusEnum::CANCELLED_NO_FEE);

        //TEST
        $this->addTransition(StatusEnum::CANCELLED, StatusEnum::NOT_CONFIRMED);
        $this->addTransition(StatusEnum::CANCELLED, StatusEnum::PROCESSING);
    }
}
