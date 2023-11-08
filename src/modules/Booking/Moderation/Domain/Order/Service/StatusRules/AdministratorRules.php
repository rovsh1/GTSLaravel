<?php

namespace Module\Booking\Moderation\Domain\Order\Service\StatusRules;

use Module\Shared\Enum\Booking\OrderStatusEnum;

class AdministratorRules extends AbstractRules implements StatusRulesInterface
{
    public function __construct()
    {
        //отмена без оплаты
//        $this->addTransition(OrderStatusEnum::IN_PROGRESS, OrderStatusEnum::CANCELLED);

        //отмена без оплаты
//        $this->addTransition(OrderStatusEnum::INVOICED, OrderStatusEnum::CANCELLED);

        $this->addTransition(OrderStatusEnum::IN_PROGRESS, OrderStatusEnum::WAITING_INVOICE);
        $this->addTransition(OrderStatusEnum::WAITING_INVOICE, OrderStatusEnum::INVOICED);
    }

    public function isEditableStatus(OrderStatusEnum $status): bool
    {
        return $status === OrderStatusEnum::IN_PROGRESS;
    }
}
