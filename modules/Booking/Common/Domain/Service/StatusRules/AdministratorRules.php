<?php

namespace Module\Booking\Common\Domain\Service\StatusRules;

use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;

class AdministratorRules extends AbstractRules
{
    public function __construct()
    {
        $this->addTransition(BookingStatusEnum::DRAFT, BookingStatusEnum::CREATED);

        $this->addTransition(BookingStatusEnum::CREATED, BookingStatusEnum::PROCESSING);
        $this->addTransition(BookingStatusEnum::CREATED, BookingStatusEnum::CANCELLED);

        $this->addTransition(BookingStatusEnum::PROCESSING, BookingStatusEnum::CANCELLED);
        //$this->addTransition(BookingStatusEnum::PROCESSING, BookingStatusEnum::WAITING_CONFIRMATION);

        $this->addTransition(BookingStatusEnum::WAITING_CONFIRMATION, BookingStatusEnum::CONFIRMED);
        $this->addTransition(BookingStatusEnum::WAITING_CONFIRMATION, BookingStatusEnum::NOT_CONFIRMED);

        //$this->addTransition(BookingStatusEnum::NOT_CONFIRMED, BookingStatusEnum::WAITING_CONFIRMATION);
        $this->addTransition(BookingStatusEnum::NOT_CONFIRMED, BookingStatusEnum::CANCELLED);

        $this->addTransition(BookingStatusEnum::CONFIRMED, BookingStatusEnum::INVOICED);
        //$this->addTransition(BookingStatusEnum::CONFIRMED, BookingStatusEnum::WAITING_PROCESSING);
        //$this->addTransition(BookingStatusEnum::CONFIRMED, BookingStatusEnum::CANCELLED_FEE);
        //$this->addTransition(BookingStatusEnum::CONFIRMED, BookingStatusEnum::CANCELLED_NO_FEE);

        //$this->addTransition(BookingStatusEnum::WAITING_PROCESSING, BookingStatusEnum::WAITING_CONFIRMATION);

        $this->addTransition(BookingStatusEnum::INVOICED, BookingStatusEnum::PAID);
        $this->addTransition(BookingStatusEnum::INVOICED, BookingStatusEnum::PARTIALLY_PAID);
        //$this->addTransition(BookingStatusEnum::INVOICED, BookingStatusEnum::WAITING_CANCELLATION);

        $this->addTransition(BookingStatusEnum::WAITING_CANCELLATION, BookingStatusEnum::CANCELLED_FEE);
        $this->addTransition(BookingStatusEnum::WAITING_CANCELLATION, BookingStatusEnum::CANCELLED_NO_FEE);

        $this->addTransition(BookingStatusEnum::CANCELLED_FEE, BookingStatusEnum::CANCELLED_NO_FEE);
        $this->addTransition(BookingStatusEnum::CANCELLED_FEE, BookingStatusEnum::REFUND_FEE);

        $this->addTransition(BookingStatusEnum::CANCELLED_NO_FEE, BookingStatusEnum::CANCELLED_FEE);
        $this->addTransition(BookingStatusEnum::CANCELLED_NO_FEE, BookingStatusEnum::REFUND_NO_FEE);
    }
}
