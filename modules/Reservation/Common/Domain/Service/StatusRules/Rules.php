<?php

namespace Module\Reservation\Common\Domain\Service\StatusRules;

use Module\Reservation\Domain\ValueObject\StatusEnum;

class Rules extends AbstractRules
{
    public function __construct()
    {
        $this->addTransition(StatusEnum::DRAFT, StatusEnum::CREATED);

        $this->addTransition(StatusEnum::CREATED, StatusEnum::PROCESSING);
        $this->addTransition(StatusEnum::CREATED, StatusEnum::CANCELLED);

        $this->addTransition(StatusEnum::PROCESSING, StatusEnum::CANCELLED);
        //$this->addTransition(StatusEnum::PROCESSING, StatusEnum::WAITING_CONFIRMATION);

        $this->addTransition(StatusEnum::REGISTERED, StatusEnum::CONFIRMED);
        $this->addTransition(StatusEnum::REGISTERED, StatusEnum::NOT_CONFIRMED);

        //$this->addTransition(StatusEnum::NOT_CONFIRMED, StatusEnum::WAITING_CONFIRMATION);
        $this->addTransition(StatusEnum::NOT_CONFIRMED, StatusEnum::CANCELLED);

        $this->addTransition(StatusEnum::CONFIRMED, StatusEnum::INVOICED);
        //$this->addTransition(StatusEnum::CONFIRMED, StatusEnum::WAITING_PROCESSING);
        //$this->addTransition(StatusEnum::CONFIRMED, StatusEnum::CANCELLED_FEE);
        //$this->addTransition(StatusEnum::CONFIRMED, StatusEnum::CANCELLED_NO_FEE);

        //$this->addTransition(StatusEnum::WAITING_PROCESSING, StatusEnum::WAITING_CONFIRMATION);

        $this->addTransition(StatusEnum::INVOICED, StatusEnum::PAID);
        $this->addTransition(StatusEnum::INVOICED, StatusEnum::PARTIALLY_PAID);
        //$this->addTransition(StatusEnum::INVOICED, StatusEnum::WAITING_CANCELLATION);

        $this->addTransition(StatusEnum::WAITING_CANCELLATION, StatusEnum::CANCELLED_FEE);
        $this->addTransition(StatusEnum::WAITING_CANCELLATION, StatusEnum::CANCELLED_NO_FEE);

        $this->addTransition(StatusEnum::CANCELLED_FEE, StatusEnum::CANCELLED_NO_FEE);
        $this->addTransition(StatusEnum::CANCELLED_FEE, StatusEnum::REFUND_FEE);

        $this->addTransition(StatusEnum::CANCELLED_NO_FEE, StatusEnum::CANCELLED_FEE);
        $this->addTransition(StatusEnum::CANCELLED_NO_FEE, StatusEnum::REFUND_NO_FEE);
    }

    public function isCancelled(StatusEnum $status): bool
    {
        return in_array($status, [
            StatusEnum::CANCELLED,
            StatusEnum::CANCELLED_FEE,
            StatusEnum::CANCELLED_NO_FEE,
            StatusEnum::REFUND_FEE,
            StatusEnum::REFUND_NO_FEE
        ]);
    }

    public function isRequested()
    {
        return $this->reservation->flag_request;
    }

    public function isInvoiced()
    {
        return $this->reservation->flag_invoice;
    }

    public function isVoucherSend()
    {
        return $this->reservation->flag_voucher;
    }

}
