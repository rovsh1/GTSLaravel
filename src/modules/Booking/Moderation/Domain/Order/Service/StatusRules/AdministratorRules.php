<?php

namespace Module\Booking\Moderation\Domain\Order\Service\StatusRules;

use Module\Shared\Enum\Order\OrderStatusEnum;

class AdministratorRules extends AbstractRules implements StatusRulesInterface
{
    public function __construct()
    {
        $this->addTransition(OrderStatusEnum::IN_PROGRESS, OrderStatusEnum::CANCELLED);
        $this->addTransition(OrderStatusEnum::INVOICED, OrderStatusEnum::CANCELLED);

        $this->addTransition(OrderStatusEnum::IN_PROGRESS, OrderStatusEnum::WAITING_INVOICE);
    }

    public function isEditableStatus(OrderStatusEnum $status): bool
    {
        return $status === OrderStatusEnum::IN_PROGRESS;
    }
}
